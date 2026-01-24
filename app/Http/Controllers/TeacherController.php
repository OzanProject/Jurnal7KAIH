<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schoolId = Auth::user()->school_id;
        $teachers = User::where('school_id', $schoolId)->where('role', 'guru')->latest()->paginate(10);
        
        return view('backend.admin.teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.admin.teachers.create');
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
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'guru',
            'school_id' => Auth::user()->school_id,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Guru berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $teacher)
    {
        // Check generic permission logic
        if ($teacher->school_id !== Auth::user()->school_id || $teacher->role !== 'guru') {
            abort(403);
        }

        return view('backend.admin.teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $teacher)
    {
        if ($teacher->school_id !== Auth::user()->school_id || $teacher->role !== 'guru') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($teacher->id)],
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $teacher->password = Hash::make($request->password);
        }

        $teacher->name = $request->name;
        $teacher->email = $request->email;
        $teacher->save();

        return redirect()->route('teachers.index')->with('success', 'Data guru berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $teacher)
    {
        if ($teacher->school_id !== Auth::user()->school_id || $teacher->role !== 'guru') {
            abort(403);
        }
        
        $teacher->delete();
        return redirect()->route('teachers.index')->with('success', 'Guru berhasil dihapus');
    }
}
