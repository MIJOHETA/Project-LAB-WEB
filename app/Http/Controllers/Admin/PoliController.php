<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PoliController extends Controller
{
    public function index()
    {
        $polis = Poli::withCount('doctors')->paginate(15);
        return view('admin.poli.index', compact('polis'));
    }

    public function create()
    {
        return view('admin.poli.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_poli' => 'required|unique:polis',
            'deskripsi' => 'nullable|string',
            'icon_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['nama_poli', 'deskripsi']);

        if ($request->hasFile('icon_image')) {
            $data['icon_image'] = $request->file('icon_image')->store('poli-icons', 'public');
        }

        Poli::create($data);

        return redirect()->route('admin.poli.index')
            ->with('success', 'Poli created successfully');
    }

    public function edit(Poli $poli)
    {
        return view('admin.poli.edit', compact('poli'));
    }

    public function update(Request $request, Poli $poli)
    {
        $request->validate([
            'nama_poli' => 'required|unique:polis,nama_poli,' . $poli->id,
            'deskripsi' => 'nullable|string',
            'icon_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['nama_poli', 'deskripsi']);

        if ($request->hasFile('icon_image')) {
            // Delete old image
            if ($poli->icon_image) {
                Storage::disk('public')->delete($poli->icon_image);
            }
            $data['icon_image'] = $request->file('icon_image')->store('poli-icons', 'public');
        }

        $poli->update($data);

        return redirect()->route('admin.poli.index')
            ->with('success', 'Poli updated successfully');
    }

    public function destroy(Poli $poli)
    {
        if ($poli->icon_image) {
            Storage::disk('public')->delete($poli->icon_image);
        }
        
        $poli->delete();
        
        return redirect()->route('admin.poli.index')
            ->with('success', 'Poli deleted successfully');
    }
}