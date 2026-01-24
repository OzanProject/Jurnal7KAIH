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
        \Illuminate\Support\Facades\DB::table('settings')
            ->whereIn('key', ['habit_1_limit', 'habit_7_limit'])
            ->update(['type' => 'time']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::table('settings')
            ->whereIn('key', ['habit_1_limit', 'habit_7_limit'])
            ->update(['type' => 'text']);
    }
};
