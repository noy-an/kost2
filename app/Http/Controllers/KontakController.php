<?php

namespace App\Http\Controllers;

use App\Models\Kontak;
use Illuminate\Http\Request;

class KontakController extends Controller
{
    public function index(Request $request)
{
    $kontaks = Kontak::query()
        ->when($request->search, function ($query) use ($request) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->orWhere('subjek', 'like', '%' . $request->search . '%');
        })
        ->latest()
        ->paginate(10);

    return view('kontak.index', compact('kontaks'));
}

    // Tampilkan form tambah kontak
    public function create()
    {
        return view('kontak.create');
    }

    // Simpan data kontak baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subjek' => 'required|string|max:255',
            'pesan' => 'required|string',
        ]);

        Kontak::create($validated);

        return redirect()->route('kontak.index')->with('success', 'Pesan berhasil dikirim.');
    }

    // Tampilkan detail kontak
    public function show($id)
    {
        $kontak = Kontak::findOrFail($id);
        return view('kontak.show', compact('kontak'));
    }

    // Tampilkan form edit kontak
    public function edit($id)
    {
        $kontak = Kontak::findOrFail($id);
        return view('kontak.edit', compact('kontak'));
    }

    // Update data kontak
    public function update(Request $request, $id)
    {
        $kontak = Kontak::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subjek' => 'required|string|max:255',
            'pesan' => 'required|string',
        ]);

        $kontak->update($validated);

        return redirect()->route('kontak.index')->with('success', 'Pesan berhasil diperbarui.');
    }

    // Hapus kontak
    public function destroy($id)
    {
        $kontak = Kontak::findOrFail($id);
        $kontak->delete();

        return redirect()->route('kontak.index')->with('success', 'Pesan berhasil dihapus.');
    }
}
