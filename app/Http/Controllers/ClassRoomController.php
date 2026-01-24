<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schoolId = Auth::user()->school_id;
        $classes = ClassRoom::where('school_id', $schoolId)
                    ->with('waliKelas')
                    ->orderByRaw('LENGTH(nama_kelas) ASC')
                    ->orderBy('nama_kelas', 'ASC')
                    ->paginate(10);
        
        return view('backend.admin.classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schoolId = Auth::user()->school_id;
        // Get teachers from the same school
        $teachers = User::where('school_id', $schoolId)->where('role', 'guru')->get();
        
        return view('backend.admin.classes.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'wali_kelas_id' => 'nullable|exists:users,id',
        ]);

        ClassRoom::create([
            'school_id' => Auth::user()->school_id,
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas_id' => $request->wali_kelas_id,
        ]);

        return redirect()->route('classes.index')->with('success', 'Kelas berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassRoom $class) // route param is 'class' but model is ClassRoom
    {
        // Ensure user owns this class
        if ($class->school_id !== Auth::user()->school_id) {
            abort(403);
        }

        $teachers = User::where('school_id', Auth::user()->school_id)->where('role', 'guru')->get();
        return view('backend.admin.classes.edit', compact('class', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassRoom $class)
    {
        if ($class->school_id !== Auth::user()->school_id) {
            abort(403);
        }

        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'wali_kelas_id' => 'nullable|exists:users,id',
        ]);

        $class->update([
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas_id' => $request->wali_kelas_id,
        ]);

        return redirect()->route('classes.index')->with('success', 'Kelas berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassRoom $class)
    {
        if ($class->school_id !== Auth::user()->school_id) {
            abort(403);
        }

        $class->delete();
        return redirect()->route('classes.index')->with('success', 'Kelas berhasil dihapus');
    }
}
