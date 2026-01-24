<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $search = $request->query('search');

        // Fetch students via User relationship or Student model
        // Using Student model allows easier access to class info
        $query = Student::whereHas('user', function($q) use ($schoolId) {
            $q->where('school_id', $schoolId);
        })
        ->join('classes', 'students.class_id', '=', 'classes.id')
        ->select('students.*')
        ->with(['user', 'classRoom']);

        // Search Filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('students.nama', 'like', "%{$search}%")
                  ->orWhere('students.nis', 'like', "%{$search}%")
                  ->orWhere('classes.nama_kelas', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('classes.nama_kelas', 'asc')
        ->orderBy('students.nama', 'asc')
        ->paginate(10)
        ->appends(['search' => $search]); // Persist search in pagination

        // Gender Counts
        $totalLaki = Student::whereHas('user', function($q) use ($schoolId) {
            $q->where('school_id', $schoolId);
        })->where('gender', 'L')->count();

        $totalPerempuan = Student::whereHas('user', function($q) use ($schoolId) {
            $q->where('school_id', $schoolId);
        })->where('gender', 'P')->count();
        
        $totalSiswa = $totalLaki + $totalPerempuan;

        return view('backend.admin.students.index', compact('students', 'totalLaki', 'totalPerempuan', 'totalSiswa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schoolId = Auth::user()->school_id;
        $classes = ClassRoom::where('school_id', $schoolId)->get();
        return view('backend.admin.students.create', compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|max:50',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'class_id' => 'required|exists:classes,id',
            'nisn' => 'nullable|string|max:50',
            'gender' => 'nullable|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'nama_ayah' => 'nullable|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'no_hp_ortu' => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'siswa',
                'school_id' => Auth::user()->school_id,
            ]);

            Student::create([
                'user_id' => $user->id,
                'class_id' => $request->class_id,
                'nis' => $request->nis,
                'nama' => $request->nama,
                'nisn' => $request->nisn,
                'gender' => $request->gender,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'nama_ayah' => $request->nama_ayah,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'nama_ibu' => $request->nama_ibu,
                'pekerjaan_ibu' => $request->pekerjaan_ibu,
                'no_hp_ortu' => $request->no_hp_ortu,
            ]);
        });

        return redirect()->route('students.index')->with('success', 'Siswa berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        // Permission check
        if ($student->user->school_id !== Auth::user()->school_id) {
            abort(403);
        }

        $classes = ClassRoom::where('school_id', Auth::user()->school_id)->get();
        return view('backend.admin.students.edit', compact('student', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        if ($student->user->school_id !== Auth::user()->school_id) {
            abort(403);
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|max:50',
            'email' => ['required', 'email', Rule::unique('users')->ignore($student->user_id)],
            'class_id' => 'required|exists:classes,id',
            'nisn' => 'nullable|string|max:50',
            'gender' => 'nullable|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'nama_ayah' => 'nullable|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'no_hp_ortu' => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($request, $student) {
            $user = $student->user;
            $user->name = $request->nama;
            $user->email = $request->email;
            
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $student->update([
                'class_id' => $request->class_id,
                'nis' => $request->nis,
                'nama' => $request->nama,
                'nisn' => $request->nisn,
                'gender' => $request->gender,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'nama_ayah' => $request->nama_ayah,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'nama_ibu' => $request->nama_ibu,
                'pekerjaan_ibu' => $request->pekerjaan_ibu,
                'no_hp_ortu' => $request->no_hp_ortu,
            ]);
        });

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        // 1. Authorization: Ensure student belongs to same school
        if ($student->user->school_id !== Auth::user()->school_id) {
            abort(403);
        }

        // 2. Safety: Prevent deleting the currently logged in user
        if ($student->user_id === Auth::id()) {
             return redirect()->back()->with('error', 'Tindakan berbahaya! Tidak dapat menghapus akun sendiri melalui data siswa.');
        }

        // 3. Safety: Prevent deleting users who are NOT students (e.g. if student is wrongly linked to an Admin/Guru)
        if ($student->user->role !== 'siswa') {
             return redirect()->back()->with('error', 'Gagal! User terkait bukan berstatus siswa. Hapus dibatalkan demi keamanan.');
        }

        // Proceed with SAFE deletion
        $student->user->delete(); // Cascade deletes student
        return redirect()->route('students.index')->with('success', 'Siswa berhasil dihapus');
    }
    /**
     * Import students from Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\StudentsImport, $request->file('file'));
            return redirect()->route('students.index')->with('success', 'Data siswa berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->route('students.index')->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    /**
     * Download Excel template.
     */
    public function downloadTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\StudentsTemplateExport, 'template_siswa.xlsx');
    }

    /**
     * Export complete student data.
     */
    public function export()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\StudentsExport, 'data_siswa_lengkap.xlsx');
    }

    /**
     * Remove multiple resources from storage.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:students,id',
        ]);

        $count = 0;
        foreach ($request->ids as $id) {
            $student = Student::find($id);
            if ($student && $student->user->school_id === Auth::user()->school_id) {
                $student->user->delete(); // Cascade deletes student
                $count++;
            }
        }

        return redirect()->route('students.index')->with('success', $count . ' siswa berhasil dihapus.');
    }

    /**
     * Reset student password to default.
     */
    public function resetPassword(Student $student)
    {
        // Authorization
        if ($student->user->school_id !== Auth::user()->school_id) {
            abort(403);
        }

        $student->user->update([
            'password' => Hash::make('12345678')
        ]);

        return redirect()->back()->with('success', 'Password siswa atas nama ' . $student->nama . ' berhasil direset menjadi 12345678');
    }

    /**
     * Show Mass Promotion Page
     */
    public function promote(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $classes = ClassRoom::where('school_id', $schoolId)
                    ->orderByRaw('LENGTH(nama_kelas) ASC')
                    ->orderBy('nama_kelas', 'ASC')
                    ->get();
        
        $sourceClassId = $request->query('source_class_id');
        $students = [];

        if ($sourceClassId) {
            $students = Student::where('class_id', $sourceClassId)
                ->whereHas('user', function($q) use ($schoolId) {
                    $q->where('school_id', $schoolId);
                })
                ->orderBy('nama', 'asc')
                ->get();
        }

        return view('backend.admin.students.promote', compact('classes', 'students', 'sourceClassId'));
    }

    /**
     * Process Mass Promotion
     */
    public function promoteStore(Request $request)
    {
        $request->validate([
            'source_class_id' => 'required|exists:classes,id',
            'target_class_id' => 'required', // Relaxed validation
            'ids' => 'required|array',
            'ids.*' => 'exists:students,id',
        ]);

        if ($request->target_class_id != 'GRADUATED') {
            $request->validate([
                'target_class_id' => 'exists:classes,id|different:source_class_id',
            ]);
        }

        // Verify ownership and perform update
        $updatedCount = 0;
        foreach ($request->ids as $studentId) {
            $student = Student::find($studentId);
            // Ensure student belongs to authorized school and is currently in source class
            if ($student && $student->user->school_id === Auth::user()->school_id && $student->class_id == $request->source_class_id) {
                
                if ($request->target_class_id === 'GRADUATED') {
                    $student->update([
                        'class_id' => null,
                        'status' => 'lulus'
                    ]);
                } else {
                    $student->update([
                        'class_id' => $request->target_class_id,
                        // Ensure status is active if previously something else (optional, but good practice)
                        'status' => 'aktif' 
                    ]);
                }
                
                $updatedCount++;
            }
        }

        $msg = $request->target_class_id === 'GRADUATED' 
            ? ' siswa berhasil diluluskan (Alumni).'
            : ' siswa berhasil dipindahkan ke kelas tujuan.';

        return redirect()->route('students.promote', ['source_class_id' => $request->source_class_id])
            ->with('success', $updatedCount . $msg);
    }
}
