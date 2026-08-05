<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JournalRecapExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->school_id;
        
        $classRooms = collect();
        if ($user->role == 'guru') {
            $classRooms = ClassRoom::where('wali_kelas_id', $user->id)->get();
            // Auto-select class for teacher if not selected
            if (!$request->class_id && $classRooms->isNotEmpty()) {
                $request->merge(['class_id' => $classRooms->first()->id]);
            }
        } else {
            $classRooms = ClassRoom::where('school_id', $schoolId)->get();
        }
        // Month list
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        // Filter params
        $filterType = $request->filter_type ?? 'month'; // month, semester
        $selectedMonth = $request->month ?? date('n');
        $selectedSemester = $request->semester ?? 1; // 1 = Ganjil, 2 = Genap
        $selectedYear = $request->year ?? date('Y');
        $selectedClassId = $request->class_id;

        $students = collect();

        if ($selectedClassId) {
            $students = Student::where('class_id', $selectedClassId)
                ->whereHas('user', function($q) use ($schoolId) {
                    $q->where('school_id', $schoolId);
                })
                ->withCount(['journals' => function($query) use ($filterType, $selectedMonth, $selectedSemester, $selectedYear) {
                    $query->whereYear('tanggal', $selectedYear);
                    
                    if ($filterType == 'semester') {
                        if ($selectedSemester == 1) { // Ganjil (Jul - Dec)
                            $query->whereMonth('tanggal', '>=', 7)->whereMonth('tanggal', '<=', 12);
                        } else { // Genap (Jan - Jun)
                            $query->whereMonth('tanggal', '>=', 1)->whereMonth('tanggal', '<=', 6);
                        }
                    } else {
                        $query->whereMonth('tanggal', $selectedMonth);
                    }
                }])
                ->orderBy('no_urut')
                ->orderBy('nama')
                ->get();
        }

        return view('backend.admin.reports.index', compact(
            'classRooms', 'months', 'filterType', 'selectedMonth', 'selectedSemester', 'selectedYear', 'selectedClassId', 'students'
        ));
    }

    public function export(Request $request)
    {
        $fileName = 'Rekap_Jurnal.xlsx';
        return Excel::download(new JournalRecapExport(
            $request->class_id, 
            $request->month, 
            $request->year,
            $request->filter_type,
            $request->semester
        ), $fileName);
    }

    public function exportPdf(Request $request) 
    {
        $schoolId = Auth::user()->school_id;
        $class = ClassRoom::find($request->class_id);
        
        $filterType = $request->filter_type ?? 'month';
        $selectedMonth = $request->month;
        $selectedSemester = $request->semester;
        $selectedYear = $request->year;

        $students = Student::where('class_id', $request->class_id)
            ->withCount(['journals' => function($query) use ($filterType, $selectedMonth, $selectedSemester, $selectedYear) {
                $query->whereYear('tanggal', $selectedYear);
                if ($filterType == 'semester') {
                    if ($selectedSemester == 1) {
                         $query->whereMonth('tanggal', '>=', 7)->whereMonth('tanggal', '<=', 12);
                    } else {
                         $query->whereMonth('tanggal', '>=', 1)->whereMonth('tanggal', '<=', 6);
                    }
                } else {
                    $query->whereMonth('tanggal', $selectedMonth);
                }
            }])
            ->orderBy('no_urut')
            ->orderBy('nama')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backend.admin.reports.pdf', compact('students', 'class', 'filterType', 'selectedMonth', 'selectedSemester', 'selectedYear'))
                ->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_Jurnal.pdf');
    }

    public function habitStats(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->school_id;
        
        $classRooms = collect();
        if ($user->role == 'guru') {
            $classRooms = ClassRoom::where('wali_kelas_id', $user->id)->get();
            if (!$request->class_id && $classRooms->isNotEmpty()) {
                $request->merge(['class_id' => $classRooms->first()->id]);
            }
        } else {
            $classRooms = ClassRoom::where('school_id', $schoolId)->get();
        }

        // Filter params
        $filterType = $request->filter_type ?? 'month';
        $selectedMonth = $request->month ?? date('n');
        $selectedSemester = $request->semester ?? 1;
        $selectedYear = $request->year ?? date('Y');
        $selectedClassId = $request->class_id;

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Thresholds
        $thresholdSudah = \App\Models\Setting::get('habit_threshold_sudah', 80);
        $thresholdCukup = \App\Models\Setting::get('habit_threshold_cukup', 50);

        $stats = [];
        $genderSummary = [
            'P' => ['count' => 0, 'sudah' => 0, 'cukup' => 0, 'belum' => 0],
            'L' => ['count' => 0, 'sudah' => 0, 'cukup' => 0, 'belum' => 0],
        ];

        $habits = \App\Models\Habit::orderBy('id')->get();
        
        // Initialize stats structure
        foreach ($habits as $habit) {
            $stats[$habit->id] = [
                'name' => $habit->name,
                'icon' => $habit->icon,
                // For Table 1 (Aggregate)
                'total' => ['sudah' => 0, 'cukup' => 0, 'belum' => 0, 'count' => 0],
                // For Table 2 (Gender Split)
                'gender' => [
                    'P' => ['sudah' => 0, 'cukup' => 0, 'belum' => 0, 'total' => 0],
                    'L' => ['sudah' => 0, 'cukup' => 0, 'belum' => 0, 'total' => 0],
                ]
            ];
        }

        if ($selectedClassId) {
            // BEST PRACTICE: Eager Loading 'journals' dan 'details' untuk menghindari ratusan N+1 Query.
            $students = Student::where('class_id', $selectedClassId)
                ->whereHas('user', function($q) use ($schoolId) {
                    $q->where('school_id', $schoolId);
                })
                ->with(['journals' => function($query) use ($filterType, $selectedMonth, $selectedSemester, $selectedYear) {
                    $query->whereYear('tanggal', $selectedYear);
                    if ($filterType == 'semester') {
                        if ($selectedSemester == 1) {
                             $query->whereMonth('tanggal', '>=', 7)->whereMonth('tanggal', '<=', 12);
                        } else {
                             $query->whereMonth('tanggal', '>=', 1)->whereMonth('tanggal', '<=', 6);
                        }
                    } else {
                        $query->whereMonth('tanggal', $selectedMonth);
                    }
                    // Hanya tarik detail yang bernilai "Ya" (1) untuk menghemat memori
                    $query->with(['details' => function($q) {
                        $q->where('nilai', 1)->select('id', 'journal_id', 'kebiasaan', 'nilai');
                    }]);
                }])
                ->get();
            
            foreach ($students as $student) {
                // Determine gender
                $g = strtoupper($student->gender);
                if ($g == 'LAKI-LAKI' || $g == 'MALE') $g = 'L';
                if ($g == 'PEREMPUAN' || $g == 'FEMALE') $g = 'P';
                
                if (!in_array($g, ['L', 'P'])) continue;
                
                $genderSummary[$g]['count']++;

                // Ambil jurnal langsung dari relasi yang sudah di-load di memori
                $journals = $student->journals;
                $totalJournals = $journals->count();
                $studentYesChecks = 0;

                // Kumpulkan semua detail jurnal untuk pencarian cepat (Collection Filter)
                $allYesDetails = $journals->flatMap->details;

                foreach ($habits as $habit) {
                    // Update Total Counts
                    $stats[$habit->id]['total']['count']++;
                    $stats[$habit->id]['gender'][$g]['total']++;

                    $percentage = 0;
                    if ($totalJournals > 0) {
                        // Menghitung jumlah 'Ya' lewat Collection (tanpa query DB lagi)
                        $yesCount = $allYesDetails->where('kebiasaan', $habit->id)->count();
                        
                        $studentYesChecks += $yesCount;
                        $percentage = ($yesCount / $totalJournals) * 100;
                    }

                    if ($percentage >= $thresholdSudah) {
                        $stats[$habit->id]['total']['sudah']++;
                        $stats[$habit->id]['gender'][$g]['sudah']++;
                    } elseif ($percentage >= $thresholdCukup) {
                        $stats[$habit->id]['total']['cukup']++;
                        $stats[$habit->id]['gender'][$g]['cukup']++;
                    } else {
                        $stats[$habit->id]['total']['belum']++;
                        $stats[$habit->id]['gender'][$g]['belum']++;
                    }
                }
                
                // Student Overall Status (Average across all habits)
                $percentageOverall = 0;
                if ($totalJournals > 0) {
                    $maxPossible = $totalJournals * $habits->count();
                    $percentageOverall = $maxPossible > 0 ? ($studentYesChecks / $maxPossible) * 100 : 0;
                }
                
                if ($percentageOverall >= $thresholdSudah) {
                    $genderSummary[$g]['sudah']++;
                } elseif ($percentageOverall >= $thresholdCukup) {
                    $genderSummary[$g]['cukup']++;
                } else {
                    $genderSummary[$g]['belum']++;
                }
            }
        }

        return view('backend.admin.reports.habit_stats', compact(
            'classRooms', 'months', 'filterType', 'selectedMonth', 
            'selectedSemester', 'selectedYear', 'selectedClassId', 
            'stats', 'genderSummary', 'thresholdSudah', 'thresholdCukup', 'habits'
        ));
    }
    public function printStudentReport(Request $request, $id)
    {
        $student = Student::with(['classRoom', 'user.school'])->findOrFail($id);
        
        // Authorization Check
        $user = Auth::user();
        if ($user->role == 'guru' && $student->classRoom->wali_kelas_id != $user->id) {
             abort(403);
        }
        if ($user->role == 'orang_tua' && $user->parent->student_id != $student->id) {
             abort(403);
        }
        if ($user->role == 'siswa' && $user->student->id != $student->id) {
            abort(403);
        }
        if ($user->role == 'admin' && $user->school_id != $student->user->school_id) {
             abort(403);
        }

        // Get Active Academic Year
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $year = $activeYear ? date('Y', strtotime($activeYear->start_date)) : date('Y');
        $semester = $activeYear ? $activeYear->semester : (date('n') > 6 ? 1 : 2);

        // Calculate Stats
        $stats = [];
        $habits = \App\Models\Habit::orderBy('id')->get();
        $thresholdSudah = \App\Models\Setting::get('habit_threshold_sudah', 80);
        $thresholdCukup = \App\Models\Setting::get('habit_threshold_cukup', 50);

        // Get journals with Eager Loading (Fix N+1)
        $journalQuery = \App\Models\Journal::where('student_id', $student->id)
                            ->with(['details' => function($q) {
                                $q->where('nilai', 1)->select('id', 'journal_id', 'kebiasaan');
                            }]);
        
        if ($activeYear && $activeYear->start_date && $activeYear->end_date) {
            $journalQuery->whereBetween('tanggal', [$activeYear->start_date, $activeYear->end_date]);
        } else {
             $journalQuery->whereYear('tanggal', date('Y'));
        }
        
        $journals = $journalQuery->get();
        $totalJournals = $journals->count();
        $allYesDetails = $journals->flatMap->details;

        foreach ($habits as $habit) {
            $percentage = 0;
            $predikat = 'Kurang';
            $description = 'Perlu ditingkatkan lagi dalam ' . strtolower($habit->name);

            if ($totalJournals > 0) {
                // Count 'Yes' dari Memory Collection (tanpa query DB lagi)
                $yesCount = $allYesDetails->where('kebiasaan', $habit->id)->count();
                $percentage = ($yesCount / $totalJournals) * 100;
            }

            if ($percentage >= $thresholdSudah) {
                $predikat = 'Sangat Baik';
                $description = 'Ananda sangat konsisten dalam ' . strtolower($habit->name) . '. Pertahankan!';
            } elseif ($percentage >= $thresholdCukup) {
                $predikat = 'Baik';
                $description = 'Ananda sudah cukup baik dalam ' . strtolower($habit->name) . ', namun masih perlu konsistensi.';
            } else {
                 $predikat = 'Perlu Perhatian';
            }

            $stats[] = [
                'habit' => $habit,
                'percentage' => round($percentage, 0),
                'predikat' => $predikat,
                'description' => $description
            ];
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backend.admin.reports.report_card_pdf', compact('student', 'stats', 'activeYear', 'totalJournals'))
                ->setPaper('a4', 'portrait');

        return $pdf->stream('Rapor_Karakter_' . $student->nama . '.pdf');
    }
}
