<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $query = Partner::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $partners = $query->get();
        return view('admin.partners.index', compact('partners'));
    }

    // Menampilkan form tambah data
    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('logo_url')->store('partners', 'public');

        Partner::create([
            'name' => $request->name,
            'logo_url' => $path,
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil ditambahkan.');
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $data = ['name' => $request->name];

        if ($request->hasFile('logo_url')) {
            if ($partner->logo_url) {
                Storage::disk('public')->delete($partner->logo_url);
            }
            $path = $request->file('logo_url')->store('partners', 'public');
            $data['logo_url'] = $path;
        }

        $partner->update($data);
        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil diupdate.');
    }

    public function destroy(Partner $partner)
    {
        if ($partner->logo_url) {
            Storage::disk('public')->delete($partner->logo_url);
        }
        $partner->delete();
        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil dihapus.');
    }
}