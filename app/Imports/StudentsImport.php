<?php

namespace App\Imports;

use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class StudentsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $schoolId = Auth::user()->school_id;

        foreach ($rows as $row) {
            // Validate required fields
            if (!isset($row['nama_lengkap']) || !isset($row['nis'])) {
                continue;
            }

            $nama = $row['nama_lengkap'];
            $nis = $row['nis'];
            $nisn = $row['nisn'] ?? null;

            // CHECK: Skip if NIS or NISN already exists in this school
            $existingStudent = Student::whereHas('user', function($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })->where(function($query) use ($nis, $nisn) {
                $query->where('nis', $nis);
                if ($nisn) {
                    $query->orWhere('nisn', $nisn);
                }
            })->exists();

            if ($existingStudent) {
                continue; // Skip this row
            }

            $className = $row['kelas'] ?? null;

            // Find Class info
            $classId = null;
            if ($className) {
                $classRoom = ClassRoom::where('school_id', $schoolId)
                    ->where('nama_kelas', 'like', '%' . $className . '%')
                    ->first();
                if ($classRoom) {
                    $classId = $classRoom->id;
                }
            }

            // Create User
            // Email generation: use provided email or default to nis@school.test
            $email = !empty($row['email']) ? $row['email'] : $nis . '@school.test'; 
            $password = !empty($row['password']) ? $row['password'] : '12345678';

            // Check if user exists
            $user = User::where('email', $email)->first();
            
            // SECURITY FIX: Prevent multiple students from sharing the same User account.
            // If the user exists, we must check if it is already linked to a DIFFERENT student.
            if ($user && $user->role === 'siswa') {
                $existingStudent = Student::where('user_id', $user->id)->first();
                // If user has a student, and that student's NIS is DIFFERENT than current row, it's a conflict!
                if ($existingStudent && $existingStudent->nis !== (string)$nis) {
                    // Conflict found: Email is taken by another student. 
                    // Solution: Generate a unique alias email for this new student.
                    $email = $nis . '.' . Str::random(3) . '@school.test';
                    $user = null; // Reset user to force creation
                }
            }
            
            if (!$user) {
                // Determine unique email
                while (User::where('email', $email)->exists()) {
                     $email = $nis . '.' . Str::random(3) . '@school.test';
                }

                $user = User::create([
                    'name' => $nama,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'role' => 'siswa',
                    'school_id' => $schoolId,
                ]);
            }

            // Create Student
            Student::create([
                'user_id' => $user->id,
                'nis' => $nis,
                'nama' => $nama,
                'no_urut' => $row['no'] ?? null,
                'class_id' => $classId,
                'nisn' => $nisn,
                'gender' => $row['jenis_kelamin'] ?? null,
            ]);
        }
    }

    private function transformDate($value)
    {
        if (empty($value)) return null;
        try {
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
            } else {
                 return \Carbon\Carbon::parse($value);
            }
        } catch (\Exception $e) {
            return null;
        }
    }
}
