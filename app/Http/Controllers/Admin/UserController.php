<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['doctor.poli', 'patient'])->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $polis = Poli::all();
        return view('admin.users.create', compact('polis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,dokter,pasien',
            'poli_id' => 'required_if:role,dokter',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Create profile based on role
        if ($request->role === 'dokter') {
            Doctor::create([
                'user_id' => $user->id,
                'poli_id' => $request->poli_id,
                'specialization' => $request->specialization,
                'phone' => $request->phone,
            ]);
        } elseif ($request->role === 'pasien') {
            Patient::create([
                'user_id' => $user->id,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'phone' => $request->phone,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        $polis = Poli::all();
        return view('admin.users.edit', compact('user', 'polis'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,dokter,pasien',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Update profile
        if ($user->role === 'dokter' && $user->doctor) {
            $user->doctor->update([
                'poli_id' => $request->poli_id,
                'specialization' => $request->specialization,
                'phone' => $request->phone,
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }
}