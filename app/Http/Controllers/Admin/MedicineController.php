<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::query();

        // Filter by stock status
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'available':
                    $query->where('stok', '>', 0);
                    break;
                case 'unavailable':
                    $query->where('stok', '=', 0);
                    break;
            }
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('nama_obat', 'like', '%' . $request->search . '%');
        }

        $medicines = $query->paginate(15);

        return view('admin.medicines.index', compact('medicines'));
    }

    public function create()
    {
        return view('admin.medicines.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_obat' => 'required|in:keras,biasa',
            'stok' => 'required|integer|min:0',
            'gambar_obat' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['nama_obat', 'deskripsi', 'tipe_obat', 'stok']);

        if ($request->hasFile('gambar_obat')) {
            $data['gambar_obat'] = $request->file('gambar_obat')->store('medicines', 'public');
        }

        Medicine::create($data);

        return redirect()->route('admin.medicines.index')
            ->with('success', 'Medicine created successfully');
    }

    public function edit(Medicine $medicine)
    {
        return view('admin.medicines.edit', compact('medicine'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_obat' => 'required|in:keras,biasa',
            'stok' => 'required|integer|min:0',
            'gambar_obat' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['nama_obat', 'deskripsi', 'tipe_obat', 'stok']);

        if ($request->hasFile('gambar_obat')) {
            // Delete old image
            if ($medicine->gambar_obat) {
                Storage::disk('public')->delete($medicine->gambar_obat);
            }
            $data['gambar_obat'] = $request->file('gambar_obat')->store('medicines', 'public');
        }

        $medicine->update($data);

        return redirect()->route('admin.medicines.index')
            ->with('success', 'Medicine updated successfully');
    }

    public function destroy(Medicine $medicine)
    {
        if ($medicine->gambar_obat) {
            Storage::disk('public')->delete($medicine->gambar_obat);
        }

        $medicine->delete();

        return redirect()->route('admin.medicines.index')
            ->with('success', 'Medicine deleted successfully');
    }
}