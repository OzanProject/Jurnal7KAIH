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
        Schema::table('habits', function (Blueprint $table) {
            $table->string('input_type')->default('checkbox')->after('description'); // checkbox, time, number
            $table->string('config_key')->nullable()->after('input_type'); // e.g. habit_1_limit
        });

        // Seed Changes
        \Illuminate\Support\Facades\DB::table('habits')->where('id', 1)->update([
            'input_type' => 'time',
            'config_key' => 'habit_1_limit'
        ]);
        \Illuminate\Support\Facades\DB::table('habits')->where('id', 7)->update([
            'input_type' => 'time',
            'config_key' => 'habit_7_limit'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('habits', function (Blueprint $table) {
            $table->dropColumn(['input_type', 'config_key']);
        });
    }
};
