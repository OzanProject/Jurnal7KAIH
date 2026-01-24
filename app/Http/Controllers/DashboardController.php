<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;
        
        // Strict Redirect logic
        return match ($role) {
            'super_admin' => redirect()->route('dashboard.super_admin'),
            'admin'       => redirect()->route('dashboard.admin'),
            'guru'        => redirect()->route('dashboard.guru'),
            'siswa'       => redirect()->route('dashboard.siswa'),
            'orang_tua'   => redirect()->route('dashboard.orang_tua'),
            default       => abort(403, 'Role tidak dikenali'),
        };
    }

    public function superAdmin()
    {
        return view('backend.superadmin.dashboard', [
            'schoolsCount'  => School::count(),
            'usersCount'    => User::count(),
            'studentsCount' => Student::count(),
            'journalsToday' => \App\Models\Journal::whereDate('created_at', now())->count(),
            'recentLogs'    => \App\Models\ActivityLog::with('user')->latest()->take(5)->get(),
            'newestUsers'   => User::latest()->take(5)->get(),
            'activeYear'    => \App\Models\AcademicYear::where('is_active', true)->first(),
        ]);
    }

    public function admin()
    {
        $schoolId = Auth::user()->school_id;
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        
        // 1. Summary Data
        $schoolsCount = School::count();
        $usersCount = User::count();
        $classesCount = ClassRoom::where('school_id', $schoolId)->count();
        $teachersCount = User::where('school_id', $schoolId)->where('role', 'guru')->count();
        $studentsCount = Student::whereHas('user', fn($q) => $q->where('school_id', $schoolId))->count();
        $journalsToday = \App\Models\Journal::whereHas('student.user', fn($q) => $q->where('school_id', $schoolId))
                            ->whereDate('tanggal', now())
                            ->count();

        // 2. Recent Activity (Latest 5 Journals)
        $recentJournals = \App\Models\Journal::whereHas('student.user', fn($q) => $q->where('school_id', $schoolId))
                            ->with(['student', 'student.classRoom'])
                            ->latest()
                            ->take(5)
                            ->get();
                            
        // 3. Activity Logs (System Wide)
        $recentLogs = \App\Models\ActivityLog::with('user')->latest()->take(5)->get();

        // 4. Habit Percentage (Pass Rate per Habit)
        $habitStats = [];
        // Get all journal IDs for this school
        $journalIds = \App\Models\Journal::whereHas('student.user', fn($q) => $q->where('school_id', $schoolId))->pluck('id');
        
        if ($journalIds->count() > 0) {
            for ($i = 1; $i <= 7; $i++) {
                $totalDetails = \App\Models\JournalDetail::whereIn('journal_id', $journalIds)
                                    ->where('kebiasaan', $i)
                                    ->count();
                $yesDetails = \App\Models\JournalDetail::whereIn('journal_id', $journalIds)
                                    ->where('kebiasaan', $i)
                                    ->where('nilai', 1)
                                    ->count();
                $habitStats[] = $totalDetails > 0 ? round(($yesDetails / $totalDetails) * 100, 1) : 0;
            }
        } else {
            $habitStats = [0, 0, 0, 0, 0, 0, 0];
        }

        // 4. Progress Graph (Last 14 Days)
        $startDate = now()->subDays(13);
        $journalProgress = \App\Models\Journal::whereHas('student.user', fn($q) => $q->where('school_id', $schoolId))
                            ->where('tanggal', '>=', $startDate->format('Y-m-d'))
                            ->groupBy('date')
                            ->orderBy('date', 'ASC')
                            ->get([
                                \Illuminate\Support\Facades\DB::raw('DATE(tanggal) as date'),
                                \Illuminate\Support\Facades\DB::raw('count(*) as count')
                            ]);
                            
        // Format for Chart (ensure all days have data)
        $dates = [];
        $counts = [];
        for($i=0; $i<14; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $dates[] = $startDate->copy()->addDays($i)->format('d M');
            $record = $journalProgress->firstWhere('date', $date);
            $counts[] = $record ? $record->count : 0;
        }

        return view('backend.admin.dashboard', compact(
            'classesCount', 'teachersCount', 'studentsCount', 'journalsToday',
            'recentJournals', 'habitStats', 'dates', 'counts', 'activeYear',
            'schoolsCount', 'usersCount', 'recentLogs'
        ));
    }

    public function guru()
    {
        $teacher = Auth::user();
        $classRoom = ClassRoom::where('wali_kelas_id', $teacher->id)->first();
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        
        $stats = [
            'total_students' => 0,
            'journals_today' => 0,
            'need_attention' => 0,
            'pending_review' => 0,
        ];
        
        $recentJournals = collect();

        if ($classRoom) {
            $studentIds = $classRoom->students()->pluck('id');
            
            $stats['total_students'] = $studentIds->count();
            
            $todayQuery = \App\Models\Journal::whereIn('student_id', $studentIds)
                                ->whereDate('tanggal', now());
            
            $stats['journals_today'] = $todayQuery->count();
            $stats['need_attention'] = (clone $todayQuery)->where('status', 'pembinaan')->count();
            $stats['pending_review'] = (clone $todayQuery)->where('status', 'menunggu')->count();
            
            $recentJournals = \App\Models\Journal::whereIn('student_id', $studentIds)
                                ->with(['student'])
                                ->latest()
                                ->take(5)
                                ->get();
        }

        return view('backend.guru.dashboard', compact('classRoom', 'stats', 'recentJournals', 'activeYear'));
    }

    public function siswa()
    {
        $student = Auth::user()->student;
        if (!$student) abort(403);
        
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $totalJournals = \App\Models\Journal::where('student_id', $student->id)->count();
        $todayJournal = \App\Models\Journal::where('student_id', $student->id)->whereDate('tanggal', now())->first();
        $isFilledToday = $todayJournal ? true : false;
        $recentJournals = \App\Models\Journal::where('student_id', $student->id)->latest()->take(3)->get();

        // Habit Stats for Chart
        $habitLabels = [];
        $habitPercentages = [];
        $habits = \App\Models\Habit::orderBy('id')->get();
        
        // Get journals in active year
        $journalQuery = \App\Models\Journal::where('student_id', $student->id);
        
        if ($activeYear && $activeYear->start_date && $activeYear->end_date) {
            $journalQuery->whereBetween('tanggal', [$activeYear->start_date, $activeYear->end_date]);
        } else {
            $journalQuery->whereYear('tanggal', date('Y'));
        }
        
        $journalIds = $journalQuery->pluck('id');
        $totalFilled = $journalIds->count();

        foreach ($habits as $habit) {
            $habitLabels[] = $habit->name;
            if ($totalFilled > 0) {
                 $yesCount = \App\Models\JournalDetail::whereIn('journal_id', $journalIds)
                                ->where('kebiasaan', $habit->id)
                                ->where('nilai', 1)
                                ->count();
                 $habitPercentages[] = round(($yesCount / $totalFilled) * 100, 1);
            } else {
                 $habitPercentages[] = 0;
            }
        }

        return view('backend.siswa.dashboard', compact('totalJournals', 'isFilledToday', 'recentJournals', 'activeYear', 'habitLabels', 'habitPercentages'));
    }

    public function orangTua()
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        return view('backend.orang_tua.dashboard', compact('activeYear'));
    }
}
