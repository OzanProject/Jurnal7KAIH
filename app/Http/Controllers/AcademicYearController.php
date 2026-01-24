<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::latest()->get();
        return view('backend.superadmin.academic_years.index', compact('academicYears'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'semester' => 'required|in:Ganjil,Genap',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($request->has('is_active')) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }

        AcademicYear::create([
            'name' => $request->name,
            'semester' => $request->semester,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'semester' => 'required|in:Ganjil,Genap',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $year = AcademicYear::findOrFail($id);

        if ($request->has('is_active') && !$year->is_active) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }

        $year->update([
            'name' => $request->name,
            'semester' => $request->semester,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $year = AcademicYear::findOrFail($id);
        if ($year->is_active) {
             return redirect()->back()->with('error', 'Tidak dapat menghapus tahun ajaran yang sedang aktif.');
        }
        $year->delete();
        return redirect()->back()->with('success', 'Tahun ajaran berhasil dihapus.');
    }

    public function activate($id)
    {
        AcademicYear::where('is_active', true)->update(['is_active' => false]);
        $year = AcademicYear::findOrFail($id);
        $year->update(['is_active' => true]);
        
        return redirect()->back()->with('success', 'Tahun ajaran aktif berhasil diganti.');
    }
}
