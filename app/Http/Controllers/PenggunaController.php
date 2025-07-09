<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function bulkDelete(Request $request)
{
    $ids = $request->input('selected');
    if ($ids) {
        User::whereIn('id', $ids)->delete();
        return redirect()->route('pengguna.index')->with('success', 'Data pengguna berhasil dihapus.');
    }
    return redirect()->route('pengguna.index')->with('error', 'Tidak ada data yang dipilih.');
}

    public function index(Request $request)
{
    $visited = session('visited_menus', []);
    if (!in_array('pengguna', $visited)) {
        $visited[] = 'pengguna';
        session(['visited_menus' => $visited]);
    }

    $users = User::query()
        ->when($request->search, function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        })
        ->latest()
        ->paginate(10);

    return view('pengguna.index', compact('users'));
}


    public function create()
    {
        return view('pengguna.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,pelanggan,pemilik',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'id_card' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'phone' => 'required|numeric',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = new User();
        $user->fill($request->except('password', 'avatar', 'id_card'));
        $user->password = Hash::make($request->password);

        if ($request->hasFile('avatar')) {
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->hasFile('id_card')) {
            $user->id_card = $request->file('id_card')->store('id_cards', 'public');
        }

        $user->save();

        return redirect()->route('pengguna.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('pengguna.show', compact('user'));
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('pengguna.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,pelanggan,pemilik',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'id_card' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'phone' => 'required|numeric',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->fill($request->except('password', 'avatar', 'id_card'));

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->hasFile('id_card')) {
            $user->id_card = $request->file('id_card')->store('id_cards', 'public');
        }

        $user->save();

        return redirect()->route('pengguna.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('pengguna.index')->with('success', 'User berhasil dihapus.');
    }
}
