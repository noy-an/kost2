<?php

namespace App\Http\Controllers;

use App\Models\Kontak;
use Illuminate\Http\Request;
use App\Models\Balasan;
use Illuminate\Support\Facades\Mail;

class KontakController extends Controller
{
   public function index(Request $request)
{
    $kontaks = Kontak::query()
        ->when($request->search, function ($query) use ($request) {
            $query->where(function ($subQuery) use ($request) {
                $subQuery->where('nama', 'like', '%' . $request->search . '%')
                         ->orWhere('email', 'like', '%' . $request->search . '%')
                         ->orWhere('subjek', 'like', '%' . $request->search . '%');
            });
        })
        ->when($request->tanggal, function ($query) use ($request) {
            $query->whereDate('created_at', $request->tanggal);
        })
        ->latest()
        ->paginate(10)
        ->appends($request->query());

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

public function sendReply(Request $request)
{
    $request->validate([
        'to' => 'required|email',
        'subject' => 'required',
        'message' => 'required',
        'email_id' => 'required|exists:kontak,id'
    ]);

    try {
        // Kirim email ke pelanggan
        Mail::raw($request->message, function($message) use ($request) {
            $message->to($request->to)
                    ->subject($request->subject)
                    ->from(config('mail.from.address'), config('mail.from.name'));
        });

        // Simpan balasan ke database
        Balasan::create([
            'kontak_id' => $request->email_id,
            'subjek' => $request->subject,
            'pesan' => $request->message,
        ]);

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false], 500);
    }
}


}
