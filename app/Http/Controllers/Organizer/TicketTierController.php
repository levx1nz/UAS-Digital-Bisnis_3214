<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TicketTier;
use Illuminate\Http\Request;

class TicketTierController extends Controller
{
    private function guardOwner(Event $event): void
    {
        abort_unless($event->organizer_id === auth()->id(), 403);
    }

    public function index(Event $event)
    {
        $this->guardOwner($event);
        $tiers = $event->ticketTiers()->orderBy('sort_order')->orderBy('starts_at')->get();
        return view('organizer.tiers.index', compact('event', 'tiers'));
    }

    public function store(Request $request, Event $event)
    {
        $this->guardOwner($event);

        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'price'      => 'required|integer|min:0',
            'quota'      => 'nullable|integer|min:1',
            'starts_at'  => 'nullable|date',
            'ends_at'    => 'nullable|date|after_or_equal:starts_at',
            'sort_order' => 'nullable|integer',
        ]);

        $event->ticketTiers()->create([
            'name'       => $data['name'],
            'price'      => $data['price'],
            'quota'      => $data['quota'] ?? null,
            'starts_at'  => $data['starts_at'] ?? null,
            'ends_at'    => $data['ends_at'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return back()->with('success', 'Kategori tiket berhasil ditambahkan.');
    }

    public function destroy(Event $event, TicketTier $tier)
    {
        $this->guardOwner($event);
        abort_unless($tier->event_id === $event->id, 404);
        $tier->delete();
        return back()->with('success', 'Kategori tiket dihapus.');
    }
}