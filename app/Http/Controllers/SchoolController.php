<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = School::latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_sekolah', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
        }

        $schools = $query->paginate(10);
        return view('backend.superadmin.schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.superadmin.schools.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'website' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);

        $data = $request->except(['logo', 'kop_surat', 'primary_color', 'secondary_color']);

        School::create($data);

        return redirect()->route('schools.index')->with('success', 'Sekolah berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(School $school)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    {
        return view('backend.superadmin.schools.edit', compact('school'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, School $school)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        $data = $request->except(['logo', 'kop_surat', 'primary_color', 'secondary_color']);

        $school->update($data);

        return redirect()->route('schools.index')->with('success', 'Data sekolah berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        if ($school->logo) {
             Storage::disk('public')->delete($school->logo);
        }
         if ($school->kop_surat) {
             Storage::disk('public')->delete($school->kop_surat);
        }
        $school->delete();
        return redirect()->route('schools.index')->with('success', 'Sekolah berhasil dihapus');
    }
}
