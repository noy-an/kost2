<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KostController extends Controller
{
    /**
     * Tampilkan semua data kost
     */
  public function index(Request $request)
{
    $kost = Kost::query()
        ->when($request->search, function ($query) use ($request) {
            $query->where('nomor_kamar', 'like', '%' . $request->search . '%')
                ->orWhere('status', 'like', '%' . $request->search . '%');
        })
        ->latest()
        ->paginate(10);

    return view('kost.index', compact('kost'));
}

    /**
     * Tampilkan form tambah kost
     */
    public function create()
    {
        return view('kost.create', [
            'title' => 'Tambah Kost'
        ]);
    }

    /**
     * Simpan data baru kost
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_kamar' => 'required|integer|unique:kost,nomor_kamar',
            'nomor_lantai' => 'required|integer',
            'harga' => 'required|integer',
            'deskripsi' => 'nullable|string',
            'ukuran_ruangan' => 'nullable|string',
            'status' => 'required|in:kosong,terisi,dibooking',
            'fasilitas' => 'nullable|string',
            'foto1' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto2' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto3' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except(['foto1', 'foto2', 'foto3']);
        $data['foto1'] = $request->file('foto1')->store('kost', 'public');
        $data['foto2'] = $request->file('foto2')->store('kost', 'public');
        $data['foto3'] = $request->file('foto3')->store('kost', 'public');

        Kost::create($data);

        return redirect()->route('kost.index')->with('success', 'Data kost berhasil ditambahkan.');
    }


    public function edit($id)
    {
        $kost = Kost::findOrFail($id);

        return view('kost.edit', [
            'title' => 'Edit Kost',
            'kost' => $kost
        ]);
    }

    /**
     * Update data kost
     */
    public function update(Request $request, $id)
    {
        $kost = Kost::findOrFail($id);

        $request->validate([
            'nomor_kamar' => 'required|integer|unique:kost,nomor_kamar,' . $kost->id,
            'nomor_lantai' => 'required|integer',
            'harga' => 'required|integer',
            'deskripsi' => 'nullable|string',
              'ukuran_ruangan' => 'nullable|string',
            'status' => 'required|in:kosong,terisi,dibooking',
            'fasilitas' => 'nullable|string',
            'foto1' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto2' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto3' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except(['foto1', 'foto2', 'foto3']);

        foreach (['foto1', 'foto2', 'foto3'] as $foto) {
            if ($request->hasFile($foto)) {
                Storage::disk('public')->delete($kost->$foto);
                $data[$foto] = $request->file($foto)->store('kost', 'public');
            }
        }

        $kost->update($data);

        return redirect()->route('kost.index')->with('success', 'Data kost berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kost = Kost::findOrFail($id);

        Storage::disk('public')->delete([$kost->foto1, $kost->foto2, $kost->foto3]);

        $kost->delete();

        return redirect()->route('kost.index')->with('success', 'Data kost berhasil dihapus.');
    }
}
