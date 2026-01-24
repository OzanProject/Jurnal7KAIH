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
        Schema::table('journals', function (Blueprint $table) {
            $table->text('catatan_orang_tua')->nullable()->after('catatan_guru');
            $table->timestamp('parent_confirmed_at')->nullable()->after('catatan_orang_tua');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journals', function (Blueprint $table) {
            $table->dropColumn(['catatan_orang_tua', 'parent_confirmed_at']);
        });
    }
};
