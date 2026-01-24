<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Journal;
use App\Models\JournalDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherJournalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $teacher = Auth::user();
        
        // Ensure user is teacher
        if ($teacher->role !== 'guru') {
            abort(403);
        }

        // Get class managed by teacher
        $classRoom = ClassRoom::where('wali_kelas_id', $teacher->id)->first();

        if (!$classRoom) {
            return view('backend.guru.journals.index', ['error' => 'Anda belum menjadi wali kelas di kelas manapun.']);
        }

        $date = $request->input('date', Carbon::now()->toDateString());

        // Get Pending Journals (All Dates)
        $pendingJournals = Journal::whereHas('student', function($q) use ($classRoom) {
                            $q->where('class_id', $classRoom->id);
                        })
                        ->where('status', 'menunggu')
                        ->with(['student.user'])
                        ->orderBy('tanggal', 'asc')
                        ->get();

        // Get students in class with their journal for the specific date
        $students = $classRoom->students()->with(['user', 'journals' => function($q) use ($date) {
            $q->where('tanggal', $date);
        }])->get();

        return view('backend.guru.journals.index', compact('classRoom', 'students', 'date', 'pendingJournals'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Journal $journal)
    {
        // Check if this journal belongs to a student in teacher's class
        $teacher = Auth::user();
        $classRoom = ClassRoom::where('wali_kelas_id', $teacher->id)->first();
        
        if (!$classRoom || $journal->student->class_id !== $classRoom->id) {
             abort(403, 'Anda tidak memiliki hak akses ke jurnal ini.');
        }

        $journal->load(['details.habit', 'student.user']);

        return view('backend.guru.journals.show', compact('journal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Journal $journal)
    {
        $request->validate([
            'status' => 'required|in:menunggu,disetujui,pembinaan',
            'catatan_guru' => 'nullable|string',
        ]);

        $journal->update([
            'status' => $request->status,
            'catatan_guru' => $request->catatan_guru,
        ]);

        return redirect()->route('teacher.journals.index', ['date' => $journal->tanggal])
                         ->with('success', 'Jurnal berhasil divalidasi.');
    }
}
