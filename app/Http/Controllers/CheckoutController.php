<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        $categories = \App\Models\Category::all();
        return view('checkout.create', compact('event', 'categories'));
    }

    public function checkCoupon(Request $request, Event $event)
    {
        $subtotal = $event->currentPrice();
        $code = strtoupper(trim($request->input('coupon_code', '')));

        $coupon = \App\Models\Coupon::where('code', $code)->first();
        if (!$coupon) {
            return response()->json(['valid' => false, 'message' => 'Kode kupon tidak ditemukan.']);
        }

        [$valid, $message] = $coupon->validateFor($subtotal, $event->id);
        if (!$valid) {
            return response()->json(['valid' => false, 'message' => $message]);
        }

        $discount           = $coupon->discountFor($subtotal);
        $priceAfterDiscount = max(0, $subtotal - $discount);
        $isFree             = $priceAfterDiscount <= 0;

        return response()->json([
            'valid'    => true,
            'message'  => $message,
            'discount' => $discount,
            'free'     => $isFree,
            'total'    => $isFree ? 0 : $priceAfterDiscount + 5000,
        ]);
    }

    public function store(Request $request, Event $event)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        if ($event->stock <= 0) {
            return back()->with('error', 'Mohon maaf, tiket untuk acara ini sudah habis.');
        }

        $tier       = $event->currentTier();
        $tierPrice  = $event->currentPrice();
        $serviceFee = 5000;

        $discount   = 0;
        $couponCode = null;

        if ($request->filled('coupon_code')) {
            $coupon = \App\Models\Coupon::where('code', strtoupper(trim($request->coupon_code)))->first();

            if (!$coupon) {
                return back()->withInput()->with('error', 'Kode kupon tidak ditemukan.');
            }

            [$valid, $message] = $coupon->validateFor($tierPrice, $event->id);
            if (!$valid) {
                return back()->withInput()->with('error', $message);
            }

            $discount   = $coupon->discountFor($tierPrice);
            $couponCode = $coupon->code;
            $coupon->increment('used_count');
        }

        $orderId            = 'TRX-' . time() . '-' . Str::random(5);
        $priceAfterDiscount = max(0, $tierPrice - $discount);

        $isFree     = $priceAfterDiscount <= 0;
        $totalPrice = $isFree ? 0 : $priceAfterDiscount + $serviceFee;

        $transaction = Transaction::create([
            'user_id'          => Auth::id(),
            'event_id'         => $event->id,
            'ticket_tier_id'   => $tier?->id,
            'ticket_tier_name' => $tier?->name,
            'original_price'   => $tierPrice,
            'coupon_code'      => $couponCode,
            'discount'         => $discount,
            'order_id'         => $orderId,
            'customer_name'    => $request->customer_name,
            'customer_email'   => $request->customer_email,
            'customer_phone'   => $request->customer_phone,
            'total_price'      => $totalPrice,
            'status'           => $isFree ? 'success' : 'Pending',
        ]);

        if ($isFree) {
            if ($event->stock > 0) {
                $event->decrement('stock');
            }

            if ($tier) {
                \App\Models\TicketTier::where('id', $tier->id)->increment('sold');
            }

            try {
                \Illuminate\Support\Facades\Mail::to($transaction->customer_email)
                    ->send(new \App\Mail\EventTicketMail($transaction));
            } catch (\Exception $e) {
                \Log::error('Gagal kirim E-Ticket gratis: ' . $e->getMessage());
            }

            return redirect()->route('checkout.success', $transaction->order_id)
                ->with('success', 'Tiket gratis berhasil diterbitkan! Cek email Anda untuk E-Ticket.');
        }

        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false; 
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email'      => $request->customer_email,
                'phone'      => $request->customer_phone,
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $transaction->update(['snap_token' => $snapToken]);
            return redirect()->route('checkout.payment', $transaction->order_id);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran jaringan: ' . $e->getMessage());
        }
    }

    public function payment($order_id)
    {
        $categories = \App\Models\Category::all();
        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();
        return view('checkout.payment', compact('transaction', 'categories'));
    }

    public function success($order_id)
    {
        $categories = \App\Models\Category::all();
        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();

        if (empty($transaction->snap_token) || (int) $transaction->total_price === 0) {
            return view('checkout.success', compact('transaction', 'categories'));
        }

        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        try {
            $status = \Midtrans\Transaction::status($order_id);

            if ($status) {
                $trx_status = is_array($status) ? ($status['transaction_status'] ?? '') : ($status->transaction_status ?? '');

                if (in_array($trx_status, ['settlement', 'capture'])) {
                    if (strtolower($transaction->status) === 'pending') {
                        $transaction->update(['status' => 'success']);

                        if ($transaction->event && $transaction->event->stock > 0) {
                            $transaction->event->stock = $transaction->event->stock - 1;
                            $transaction->event->save();
                        }

                        if ($transaction->ticket_tier_id) {
                            \App\Models\TicketTier::where('id', $transaction->ticket_tier_id)->increment('sold');
                        }

                        try {
                            \Illuminate\Support\Facades\Mail::to($transaction->customer_email)
                                ->send(new \App\Mail\EventTicketMail($transaction));
                        } catch (\Exception $e) {
                            \Log::error('Gagal kirim email Bypass: ' . $e->getMessage());
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Gagal memproses pengecekan status.');
        }

        return view('checkout.success', compact('transaction', 'categories'));
    }

    public function myTickets()
    {
        $transactions = \App\Models\Transaction::where(function($query) {
                $query->where('user_id', \Illuminate\Support\Facades\Auth::id())
                      ->orWhere('customer_email', \Illuminate\Support\Facades\Auth::user()->email);
            })
            ->whereIn('status', ['success', 'settlement', 'capture'])
            ->latest()
            ->get();

        return view('my-tickets', compact('transactions'));
    }
}