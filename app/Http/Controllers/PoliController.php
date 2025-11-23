<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PoliController extends Controller
{
    public function index()
    {
        $polis = Poli::all();
        return view('admin.polis.index', compact('polis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        // Handle Upload Gambar
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('polis', 'public');
        }

        Poli::create($data);

        return back()->with('success', 'Poli berhasil ditambahkan');
    }

    public function update(Request $request, Poli $poli)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($poli->image) {
                Storage::disk('public')->delete($poli->image);
            }
            $data['image'] = $request->file('image')->store('polis', 'public');
        }

        $poli->update($data);

        return back()->with('success', 'Poli berhasil diperbarui');
    }

    public function destroy(Poli $poli)
    {
        if ($poli->image) {
            Storage::disk('public')->delete($poli->image);
        }
        $poli->delete();
        return back()->with('success', 'Poli berhasil dihapus');
    }
}