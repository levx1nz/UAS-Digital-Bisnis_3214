<?php
namespace App\Http\Controllers;
use App\Models\Pengurus;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class PengurusController extends Controller
{
    public function index() {
        $penguruses = Pengurus::with('jabatan')->get();
        return view('admin.pengurus.index', compact('penguruses'));
    }

    public function create() {
        $jabatans = Jabatan::all();
        return view('admin.pengurus.create', compact('jabatans'));
    }

    public function store(Request $request) {
        Pengurus::create($request->validate([
            'nama_pengurus' => 'required',
            'jabatan_id' => 'required|exists:jabatans,id',
            'deskripsi' => 'nullable',
            'gaji' => 'required|numeric'
        ]));
        return redirect()->route('admin.pengurus.index')->with('success', 'Pengurus ditambah!');
    }

    public function edit(Pengurus $pengurus) {
        $jabatans = Jabatan::all();
        return view('admin.pengurus.edit', compact('pengurus', 'jabatans'));
    }

    public function update(Request $request, Pengurus $pengurus) {
        $pengurus->update($request->validate([
            'nama_pengurus' => 'required',
            'jabatan_id' => 'required|exists:jabatans,id',
            'deskripsi' => 'nullable',
            'gaji' => 'required|numeric'
        ]));
        return redirect()->route('admin.pengurus.index')->with('success', 'Pengurus diupdate!');
    }

    public function destroy(Pengurus $pengurus) {
        $pengurus->delete();
        return redirect()->route('admin.pengurus.index')->with('success', 'Pengurus dihapus!');
    }
}