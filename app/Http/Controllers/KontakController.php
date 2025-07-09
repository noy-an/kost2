<?php

namespace App\Http\Controllers;

use App\Models\Kontak;
use Illuminate\Http\Request;

class KontakController extends Controller
{
    public function index(Request $request)
{
    $visited = session('visited_menus', []);
    if (!in_array('kontak', $visited)) {
        $visited[] = 'kontak';
        session(['visited_menus' => $visited]);
    }

    $kontaks = Kontak::query()
        ->when($request->search, function ($query) use ($request) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('subjek', 'like', '%' . $request->search . '%');
        })
        ->when($request->tanggal, function ($query) use ($request) {
            $query->whereDate('created_at', $request->tanggal);
        })
        ->latest()
        ->paginate(10);

    return view('kontak.index', compact('kontaks'));
}


    public function create()
    {
        return view('kontak.create');
    }

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

    public function show($id)
    {
        $kontak = Kontak::findOrFail($id);
        return view('kontak.show', compact('kontak'));
    }

    public function edit($id)
    {
        $kontak = Kontak::findOrFail($id);
        return view('kontak.edit', compact('kontak'));
    }

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

    public function destroy($id)
    {
        $kontak = Kontak::findOrFail($id);
        $kontak->delete();

        return redirect()->route('kontak.index')->with('success', 'Pesan berhasil dihapus.');
    }

public function destroyAll()
{
    Kontak::truncate();

    if (request()->expectsJson()) {
        return response()->json(['success' => true]);
    }

    return redirect()->route('kontak.index')->with('success', 'Semua pesan berhasil dihapus.');
}

}
