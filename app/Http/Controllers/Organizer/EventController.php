<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    private function authorizeOwnership(Event $event): void
    {
        abort_if($event->organizer_id !== Auth::id(), 403, 'Anda tidak berhak mengelola event ini.');
    }

    public function index(Request $request)
    {
        $query = Event::with('category')->where('organizer_id', Auth::id());

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        $events = $query->latest()->paginate(10)->withQueryString();
        return view('organizer.events.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('organizer.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|numeric|min:1',
            'poster'      => 'nullable|image|max:2048',
            'is_published' => 'nullable|boolean',
        ]);

        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $data['organizer_id'] = Auth::id();           // ⭐ kunci kepemilikan
        $data['is_published'] = $request->boolean('is_published');

        Event::create($data);
        return redirect()->route('organizer.events.index')->with('success', 'Event berhasil dibuat.');
    }

    public function edit(Event $event)
    {
        $this->authorizeOwnership($event);
        $categories = Category::all();
        return view('organizer.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorizeOwnership($event);

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|numeric|min:1',
            'poster'      => 'nullable|image|max:2048',
            'is_published' => 'nullable|boolean',
        ]);

        if ($request->hasFile('poster')) {
            if ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) {
                Storage::disk('public')->delete($event->poster_path);
            }
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $data['is_published'] = $request->boolean('is_published');
        $event->update($data);

        return redirect()->route('organizer.events.index')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        $this->authorizeOwnership($event);
        if ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) {
            Storage::disk('public')->delete($event->poster_path);
        }
        $event->delete();
        return redirect()->route('organizer.events.index')->with('success', 'Event berhasil dihapus.');
    }

    public function togglePublish(Event $event)
    {
        $this->authorizeOwnership($event);
        $event->update(['is_published' => !$event->is_published]);
        return back()->with('success', $event->is_published ? 'Event dipublikasikan.' : 'Event disembunyikan.');
    }
}