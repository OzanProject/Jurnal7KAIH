<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\School;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\ParentModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create School
        $school = School::firstOrCreate(
            ['nama_sekolah' => 'SD Negeri 01 Contoh'],
            ['alamat' => 'Jl. Pendidikan No. 1, Jakarta']
        );



        // 3. Create School Admin
        $admin = User::firstOrCreate(
            ['email' => 'ardiansyahdzan@gmail.com'],
            [
                'name' => 'Admin Sekolah',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'school_id' => $school->id,
            ]
        );

        // 4. Create Teacher (Wali Kelas)
        $teacherUser = User::firstOrCreate(
            ['email' => 'guru@example.com'],
            [
                'name' => 'Budi Guru',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'school_id' => $school->id,
            ]
        );

        // Create Class and assign teacher
        $class = ClassRoom::firstOrCreate(
            ['school_id' => $school->id, 'nama_kelas' => '1A'],
            ['wali_kelas_id' => $teacherUser->id]
        );

        // 5. Create Student
        $studentUser = User::firstOrCreate(
            ['email' => 'siswa@example.com'],
            [
                'name' => 'Andi Siswa',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'school_id' => $school->id,
            ]
        );

        $student = Student::firstOrCreate(
            ['user_id' => $studentUser->id],
            [
                'class_id' => $class->id,
                'nis' => '123456',
                'nama' => 'Andi Siswa',
            ]
        );

        // 6. Create Parent
        $parentUser = User::firstOrCreate(
            ['email' => 'orangtua@example.com'],
            [
                'name' => 'Ayah Andi',
                'password' => Hash::make('password'),
                'role' => 'orang_tua',
                'school_id' => $school->id,
            ]
        );

        ParentModel::firstOrCreate(
            ['user_id' => $parentUser->id],
            ['student_id' => $student->id]
        );
    }
}
