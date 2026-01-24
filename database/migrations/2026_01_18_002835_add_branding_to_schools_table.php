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
        Schema::table('schools', function (Blueprint $table) {
            $table->string('kop_surat')->nullable()->after('logo');
            $table->string('primary_color')->default('#3498db')->after('kop_surat');
            $table->string('secondary_color')->default('#2ecc71')->after('primary_color');
            $table->string('website')->nullable()->after('secondary_color');
            $table->string('email')->nullable()->after('website');
            $table->string('phone')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['kop_surat', 'primary_color', 'secondary_color', 'website', 'email', 'phone']);
        });
    }
};
