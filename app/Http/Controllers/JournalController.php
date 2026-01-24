<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Anda bukan siswa.');
        }

        $query = Journal::where('student_id', $student->id);

        // Filters
        if ($request->filled('month')) {
            $query->whereMonth('tanggal', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('tanggal', $request->year);
        }

        // Count successful habits (where nilai = 1)
        $journals = $query->withCount(['details as habits_count' => function ($q) {
            $q->where('nilai', 1);
        }])
        ->latest('tanggal')
        ->paginate(10);

        return view('backend.siswa.journals.index', compact('journals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Anda bukan siswa.');
        }

        // Always show all habits to allow backfilling/editing
        $habits = \App\Models\Habit::all();

        return view('backend.siswa.journals.create', compact('habits'));

        return view('backend.siswa.journals.create', compact('habits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $request->validate([
            'tanggal' => 'required|date|before_or_equal:today',
            'habits' => 'required|array',
            'habits.*' => 'nullable', 
            'catatan_siswa' => 'nullable|string|max:1000',
        ]);

        $today = $request->tanggal; // Use selected date
        
        $inputHabits = $request->input('habits', []);
        
        // Strict Validation Removed for Smart Partial Filling
        if (empty($inputHabits)) {
             return redirect()->back()->with('error', 'Pilih minimal satu kebiasaan.')->withInput();
        }

        $student = Auth::user()->student;
        $isPerfect = true; 
        
        // Fetch only submitted habits
        $habitIds = array_keys($inputHabits);
        $habits = \App\Models\Habit::whereIn('id', $habitIds)->get();

        DB::transaction(function () use ($request, $student, $today, $habits, $inputHabits, &$isPerfect) {
            // Find or Create Journal Header
            $journal = Journal::firstOrCreate(
                ['student_id' => $student->id, 'tanggal' => $today],
                ['status' => 'menunggu', 'catatan_siswa' => $request->catatan_siswa ?? '-']
            );

            if ($request->filled('catatan_siswa')) {
                $journal->update(['catatan_siswa' => $request->catatan_siswa]);
            }

            // Process Details
            foreach ($habits as $habit) {
                $habitData = $inputHabits[$habit->id] ?? null;
                
                $status = isset($habitData['status']) ? (int)$habitData['status'] : 0;
                $note = $habitData['note'] ?? null;
                $time = $habitData['time'] ?? null;
                
                $nilai = 0;
                $actualValue = null;

                if ($status == 1) {
                    // Executed
                    $nilai = 1;
                    if ($habit->input_type == 'time') {
                        $actualValue = $time;
                    }
                } else {
                    // Not Executed
                    $nilai = 0;
                    $isPerfect = false; 
                }

                // Update or Create Detail
                JournalDetail::updateOrCreate(
                    ['journal_id' => $journal->id, 'kebiasaan' => $habit->id],
                    [
                        'nilai' => $nilai,
                        'actual_value' => $actualValue,
                        'note' => $note
                    ]
                );
            }

            // Update Journal Status
            // If ALL habits are 1 -> Menunggu
            // If ANY habit is 0 -> Perlu Pembinaan
            // If ALL habits are 1 -> Menunggu
            // If ANY habit is 0 -> Pembinaan
            $newStatus = $isPerfect ? 'menunggu' : 'pembinaan';
            
            // If previous status was 'disetujui', generally we shouldn't downgrade automatically without teacher knowing,
            // but strict rules imply daily truth. Let's update it.
            $journal->update(['status' => $newStatus]);
        });
        
        $msg = $isPerfect ? 'Luar biasa! Pertahankan semangatmu.' : 'Jurnal tersimpan. Terus perbaiki diri ya!';
        return redirect()->route('journals.index')->with('success', $msg);
    }

    /**
     * Display the specified resource.
     */
    public function show(Journal $journal)
    {
        $journal->load(['details.habit']);
        // Ensure student owns this journal
        if ($journal->student_id !== Auth::user()->student->id) {
            abort(403);
        }
        
        return view('backend.siswa.journals.show', compact('journal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Journal $journal)
    {
        // Typically journals are locked after submission or approval.
        // For simplicity, let's assume they can't edit for now, or only if 'menunggu'.
        if ($journal->status !== 'menunggu') {
             return redirect()->route('journals.index')->with('error', 'Jurnal sudah divalidasi, tidak bisa diedit.');
        }
        return abort(404); // Or implement edit logic
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Journal $journal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Journal $journal)
    {
        // Ensure student owns this journal
        if ($journal->student_id !== Auth::user()->student->id) {
            abort(403);
        }

        // Only allow deleting if status is 'menunggu' - REMOVED CHECK
        // if ($journal->status !== 'menunggu') {
        //     return redirect()->back()->with('error', 'Jurnal yang sudah divalidasi tidak dapat dihapus.');
        // }

        $journal->delete();

        return redirect()->route('journals.index')->with('success', 'Jurnal berhasil dihapus.');
    }
}
