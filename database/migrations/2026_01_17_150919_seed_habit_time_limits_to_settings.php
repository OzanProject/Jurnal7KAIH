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
                'key' => 'habit_1_limit',
                'value' => '05:00',
                'type' => 'text', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'system',
                'key' => 'habit_7_limit',
                'value' => '21:00',
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
         \Illuminate\Support\Facades\DB::table('settings')->whereIn('key', ['habit_1_limit', 'habit_7_limit'])->delete();
    }
};
