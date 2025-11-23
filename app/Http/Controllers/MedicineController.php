<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::latest()->paginate(10);
        return view('admin.medicines.index', compact('medicines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:keras,biasa,luar',
            'stock' => 'required|integer|min:0',
            'price' => 'required|integer|min:0',
        ]);

        Medicine::create($request->all());

        return back()->with('success', 'Obat berhasil ditambahkan');
    }

    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'name' => 'required|string',
            'stock' => 'required|integer',
        ]);

        $medicine->update($request->all());

        return back()->with('success', 'Data obat diperbarui');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return back()->with('success', 'Obat dihapus dari sistem');
    }
}