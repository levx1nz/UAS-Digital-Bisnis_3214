<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\PengurusController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\Admin\OrganizerController;
use App\Http\Controllers\Organizer\DashboardController as OrganizerDashboardController;
use App\Http\Controllers\Organizer\EventController as OrganizerEventController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profil', function () { return view('profil'); });
Route::get('/katalog', function (\Illuminate\Http\Request $request) {
    $categories = \App\Models\Category::all();

    $query = \App\Models\Event::with(['category', 'organizer'])
        ->withCount('reviews')
        ->withAvg('reviews', 'rating')
        ->publiclyVisible()
        ->orderBy('date', 'asc');

    if ($request->filled('category')) {
        $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
    }

    $events = $query->get();

    return view('katalog', compact('events', 'categories'));
})->name('katalog');
Route::get('/bantuan', function () { return view('bantuan'); });

Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/my-ticket/{transaction}', [EventController::class, 'ticket'])->name('ticket');
Route::get('/payment/{order_id}', [\App\Http\Controllers\CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/success/{order_id}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/midtrans/callback', [\App\Http\Controllers\MidtransWebhookController::class, 'handle']);
Route::post('/checkout/{event}/cek-kupon', [CheckoutController::class, 'checkCoupon'])->name('checkout.coupon');

Route::middleware('guest')->group(function () {
    Route::get('/login', [UserAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserAuthController::class, 'login'])->name('user.login.post');
    Route::get('/register', [UserAuthController::class, 'showRegister'])->name('user.register');
    Route::post('/register', [UserAuthController::class, 'register'])->name('user.register.post');
    Route::get('/auth/google', [UserAuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google/callback', [UserAuthController::class, 'handleGoogleCallback']);
    Route::get('/register-penyelenggara', [UserAuthController::class, 'showRegisterOrganizer'])->name('organizer.register');
    Route::post('/register-penyelenggara', [UserAuthController::class, 'registerOrganizer'])->name('organizer.register.post');
});

Route::post('/logout', [UserAuthController::class, 'logout'])->name('user.logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/my-tickets', [\App\Http\Controllers\CheckoutController::class, 'myTickets'])->name('my-tickets');
    Route::post('/events/{event}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});

Route::middleware('auth')->prefix('dashboard')->name('organizer.')->group(function () {
    Route::get('/menunggu-persetujuan', [OrganizerDashboardController::class, 'pending'])->name('pending');

    Route::middleware('organizer')->group(function () {
        Route::get('/', [OrganizerDashboardController::class, 'index'])->name('dashboard');

        Route::get('/events', [OrganizerEventController::class, 'index'])->name('events.index');
        Route::get('/events/create', [OrganizerEventController::class, 'create'])->name('events.create');
        Route::post('/events', [OrganizerEventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [OrganizerEventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [OrganizerEventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [OrganizerEventController::class, 'destroy'])->name('events.destroy');
        Route::patch('/events/{event}/publish', [OrganizerEventController::class, 'togglePublish'])->name('events.publish');
        Route::get('/events/{event}/tiers', [\App\Http\Controllers\Organizer\TicketTierController::class, 'index'])->name('events.tiers.index');
        Route::post('/events/{event}/tiers', [\App\Http\Controllers\Organizer\TicketTierController::class, 'store'])->name('events.tiers.store');
        Route::delete('/events/{event}/tiers/{tier}', [\App\Http\Controllers\Organizer\TicketTierController::class, 'destroy'])->name('events.tiers.destroy');
        Route::get('/coupons', [\App\Http\Controllers\Organizer\CouponController::class, 'index'])->name('coupons.index');
        Route::get('/coupons/create', [\App\Http\Controllers\Organizer\CouponController::class, 'create'])->name('coupons.create');
        Route::post('/coupons', [\App\Http\Controllers\Organizer\CouponController::class, 'store'])->name('coupons.store');
        Route::patch('/coupons/{coupon}/toggle', [\App\Http\Controllers\Organizer\CouponController::class, 'toggle'])->name('coupons.toggle');
        Route::delete('/coupons/{coupon}', [\App\Http\Controllers\Organizer\CouponController::class, 'destroy'])->name('coupons.destroy');
    });
});

Route::prefix('admin')->name('admin.')->group(function () {
    
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.post');
    });

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['admin'])->group(function () {
        
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard', [DashboardController::class, 'index']);
        Route::get('/users', [DashboardController::class, 'users'])->name('users.index');
        Route::get('/reviews', [DashboardController::class, 'reviews'])->name('reviews.index');
        Route::delete('/reviews/{review}', [DashboardController::class, 'destroyReview'])->name('reviews.destroy');
        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\DashboardController::class, 'destroyUser'])->name('users.destroy');
        
        Route::resource('events', AdminEventController::class);
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::resource('categories', CategoryController::class);
        Route::resource('partners', PartnerController::class);

        Route::resource('jabatan', JabatanController::class);
        Route::resource('pengurus', PengurusController::class)->parameters([
            'pengurus' => 'pengurus'
        ]);
        Route::get('/organizers', [OrganizerController::class, 'index'])->name('organizers.index');
        Route::get('/organizers/{organizer}', [OrganizerController::class, 'show'])->name('organizers.show');
        Route::middleware('superadmin')->group(function () {
            Route::patch('/organizers/{organizer}/approve', [OrganizerController::class, 'approve'])->name('organizers.approve');
            Route::patch('/organizers/{organizer}/reject', [OrganizerController::class, 'reject'])->name('organizers.reject');
        });
        
    });
});