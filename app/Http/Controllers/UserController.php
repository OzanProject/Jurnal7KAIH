<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('school')
                    ->orderByRaw("CASE WHEN role = 'super_admin' THEN 0 WHEN role = 'admin' THEN 1 ELSE 2 END")
                    ->latest();

        // Optional filter if needed later
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(10);
        return view('backend.superadmin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = School::all();
        // NOTE: You need to create this view next
        // return view('backend.superadmin.users.create', compact('schools'));
        return view('backend.superadmin.users.create', compact('schools')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,guru,siswa,orang_tua',
            'school_id' => 'required|exists:schools,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'school_id' => $request->school_id,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $schools = School::all();
        return view('backend.superadmin.users.edit', compact('user', 'schools'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,guru,siswa,orang_tua',
            'school_id' => 'required|exists:schools,id',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->school_id = $request->school_id;
        $user->save();

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }
}
