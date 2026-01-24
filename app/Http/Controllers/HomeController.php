<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Student;
use App\Models\User;
use App\Models\Jurnal; // Assuming Jurnal model exists for counts
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'schools_count' => School::count(),
            'students_count' => Student::count(),
            'teachers_count' => User::where('role', 'guru')->count(),
            // If Jurnal model doesn't exist or table is different, we can adjust. 
            // Checking previous context, 'journals' table usually exists.
            // Using a safe fallback or simply avoiding it if unsure. 
            // Let's stick to the requested main stats: Schools, Users (Teachers), Students.
        ];

        return view('welcome', $data);
    }
}
