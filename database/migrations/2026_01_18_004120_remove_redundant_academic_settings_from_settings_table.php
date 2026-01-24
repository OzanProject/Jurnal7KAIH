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
        // Remove redundant settings if they exist
        DB::table('settings')->whereIn('key', ['semester_aktif', 'tahun_ajaran_aktif'])->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-insert is optional, or leave empty as it's a cleanup
    }
};
