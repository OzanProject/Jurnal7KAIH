<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HabitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $habits = [
            [
                'id' => 1,
                'name' => 'Bangun Pagi',
                'icon' => 'feather-sun',
                'description' => 'Membiasakan diri bangun sebelum matahari terbit.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Beribadah',
                'icon' => 'feather-moon',
                'description' => 'Melaksanakan ibadah tepat waktu.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Berolahraga',
                'icon' => 'feather-activity',
                'description' => 'Melakukan aktivitas fisik untuk menjaga kebugaran.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Makan Sehat & Bergizi',
                'icon' => 'feather-coffee', // or feather-smile check icons later
                'description' => 'Mengkonsumsi makanan 4 sehat 5 sempurna.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Gemar Belajar',
                'icon' => 'feather-book',
                'description' => 'Membaca buku atau mengulang pelajaran sekolah.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'name' => 'Bermasyarakat (Bersosialisasi)',
                'icon' => 'feather-users',
                'description' => 'Berinteraksi positif dengan teman, keluarga, dan lingkungan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'name' => 'Tidur Cepat',
                'icon' => 'feather-clock',
                'description' => 'Tidur tidak larut malam agar segar esok hari.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert or update to ensure IDs are consistent
        foreach ($habits as $habit) {
            DB::table('habits')->updateOrInsert(['id' => $habit['id']], $habit);
        }
    }
}
