<?php
namespace App\Http\Controllers;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index() {
        $jabatans = Jabatan::all();
        return view('admin.jabatan.index', compact('jabatans'));
    }

    public function create() {
        return view('admin.jabatan.create');
    }

    public function store(Request $request) {
        Jabatan::create($request->validate(['nama_jabatan' => 'required']));
        return redirect()->route('admin.jabatan.index')->with('success', 'Jabatan ditambah!');
    }

    public function edit(Jabatan $jabatan) {
        return view('admin.jabatan.edit', compact('jabatan'));
    }

    public function update(Request $request, Jabatan $jabatan) {
        $jabatan->update($request->validate(['nama_jabatan' => 'required']));
        return redirect()->route('admin.jabatan.index')->with('success', 'Jabatan diupdate!');
    }

    public function destroy(Jabatan $jabatan) {
        $jabatan->delete();
        return redirect()->route('admin.jabatan.index')->with('success', 'Jabatan dihapus!');
    }
}