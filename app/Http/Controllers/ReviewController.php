<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500'
        ]);

        $existingReview = Review::where('user_id', Auth::id())->where('event_id', $event->id)->first();
        
        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk event ini.');
        }

        Review::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil disimpan.');
    }
}