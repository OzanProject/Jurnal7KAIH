<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \Illuminate\Support\Facades\DB::table('settings')->insert([
            [
                'group' => 'system',
                'key' => 'tahun_ajaran_aktif',
                'value' => '2025/2026',
                'type' => 'text',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'system',
                'key' => 'semester_aktif',
                'value' => 'Ganjil',
                'type' => 'text',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         \Illuminate\Support\Facades\DB::table('settings')->whereIn('key', ['tahun_ajaran_aktif', 'semester_aktif'])->delete();
    }
};
