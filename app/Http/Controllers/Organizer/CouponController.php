<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::where('organizer_id', auth()->id())->with('event');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('event', function ($e) use ($search) {
                        $e->where('title', 'LIKE', '%' . $search . '%');
                    });
            });
        }

        $coupons = $query->latest()->get();

        return view('organizer.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $events = Event::where('organizer_id', auth()->id())->orderBy('title')->get();
        return view('organizer.coupons.create', compact('events'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'         => ['required', 'string', 'max:50', 'alpha_num', Rule::unique('coupons', 'code')],
            'type'         => 'required|in:percent,fixed',
            'value'        => 'required|integer|min:1',
            'event_id'     => 'nullable|exists:events,id',
            'min_purchase' => 'nullable|integer|min:0',
            'max_usage'    => 'nullable|integer|min:1',
            'expires_at'   => 'nullable|date',
        ]);

        if ($data['type'] === 'percent' && $data['value'] > 100) {
            return back()->withInput()->with('error', 'Diskon persen tidak boleh lebih dari 100%.');
        }

        if (!empty($data['event_id'])) {
            $owns = Event::where('id', $data['event_id'])
                ->where('organizer_id', auth()->id())->exists();
            abort_unless($owns, 403);
        }

        Coupon::create([
            'organizer_id' => auth()->id(),
            'event_id'     => $data['event_id'] ?? null,
            'code'         => strtoupper($data['code']),
            'type'         => $data['type'],
            'value'        => $data['value'],
            'min_purchase' => $data['min_purchase'] ?? 0,
            'max_usage'    => $data['max_usage'] ?? null,
            'expires_at'   => $data['expires_at'] ?? null,
            'is_active'    => $request->boolean('is_active', true),
        ]);

        return redirect()->route('organizer.coupons.index')->with('success', 'Kupon berhasil dibuat.');
    }

    public function toggle(Coupon $coupon)
    {
        abort_unless($coupon->organizer_id === auth()->id(), 403);
        $coupon->update(['is_active' => !$coupon->is_active]);
        return back()->with('success', 'Status kupon diperbarui.');
    }

    public function destroy(Coupon $coupon)
    {
        abort_unless($coupon->organizer_id === auth()->id(), 403);
        $coupon->delete();
        return back()->with('success', 'Kupon dihapus.');
    }
}