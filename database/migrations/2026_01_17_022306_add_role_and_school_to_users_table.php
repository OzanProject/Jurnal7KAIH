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
        Schema::table('users', function (Blueprint $table) {
            // role enum: super_admin, admin, guru, siswa, orang_tua
            $table->string('role')->default('siswa')->after('password');
            $table->foreignId('school_id')->nullable()->after('role')->constrained('schools')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn(['role', 'school_id']);
        });
    }
};
