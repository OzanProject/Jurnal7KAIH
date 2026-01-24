<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\ParentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $parent = ParentModel::where('user_id', $user->id)->first();

        // Fallback if no parent record linked
        if (!$parent || !$parent->student) {
            return view('backend.orang_tua.dashboard', ['error' => 'Data siswa tidak ditemukan. Hubungi Admin Sekolah.']);
        }

        $student = $parent->student;
        
        // Stats
        $stats = [
            'total_journals' => Journal::where('student_id', $student->id)->count(),
            'journals_this_month' => Journal::where('student_id', $student->id)->whereMonth('tanggal', now()->month)->count(),
            'compliance_rate' => 0, // Calculate later if needed
            'need_attention' => Journal::where('student_id', $student->id)->where('status', 'pembinaan')->count(),
        ];

        // Recent Journals
        $recentJournals = Journal::where('student_id', $student->id)
                            ->with(['details'])
                            ->latest()
                            ->take(5)
                            ->get();

        return view('backend.orang_tua.dashboard', compact('parent', 'student', 'stats', 'recentJournals'));
    }

    public function index()
    {
        $user = Auth::user();
        $parent = ParentModel::where('user_id', $user->id)->first();
        
        if (!$parent || !$parent->student) abort(403);

        $journals = Journal::where('student_id', $parent->student_id)
                    ->latest()
                    ->paginate(10);

        return view('backend.orang_tua.journals.index', compact('journals', 'parent'));
    }

    public function showJournal($id)
    {
        $user = Auth::user();
        $parent = ParentModel::where('user_id', $user->id)->first();
        
        if (!$parent) abort(403);

        $journal = Journal::where('id', $id)
                    ->where('student_id', $parent->student_id)
                    ->with(['details.habit'])
                    ->firstOrFail();

        return view('backend.orang_tua.journals.show', compact('journal', 'parent'));
    }

    public function storeConfirmation(Request $request, $id)
    {
        $request->validate([
            'catatan_orang_tua' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $parent = ParentModel::where('user_id', $user->id)->first();
        
        if (!$parent) abort(403);

        $journal = Journal::where('id', $id)
                    ->where('student_id', $parent->student_id)
                    ->firstOrFail();

        $journal->update([
            'catatan_orang_tua' => $request->catatan_orang_tua,
            'parent_confirmed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Terima kasih, konfirmasi Anda telah tersimpan.');
    }
}
