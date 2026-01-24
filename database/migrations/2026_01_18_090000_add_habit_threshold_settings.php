<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert default threshold settings
        DB::table('settings')->insert([
            [
                'key' => 'habit_threshold_sudah',
                'value' => '80', // Default 80%
                'type' => 'number',
                'group' => 'system',
            ],
            [
                'key' => 'habit_threshold_cukup',
                'value' => '50', // Default 50%
                'type' => 'number',
                'group' => 'system',
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', ['habit_threshold_sudah', 'habit_threshold_cukup'])->delete();
    }
};
