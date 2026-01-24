<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'app_name', 'value' => 'Jurnal 7 Kebiasaan', 'type' => 'text', 'group' => 'general'],
            ['key' => 'app_description', 'value' => 'Aplikasi Jurnal Harian Siswa', 'type' => 'textarea', 'group' => 'general'],
            ['key' => 'footer_text', 'value' => 'Copyright © 2026 Jurnal 7 Kebiasaan.', 'type' => 'text', 'group' => 'general'],
            
            // Appearance
            ['key' => 'app_logo', 'value' => 'template-admin/assets/images/logo-full.png', 'type' => 'image', 'group' => 'appearance'],
            ['key' => 'app_favicon', 'value' => 'template-admin/assets/images/favicon.ico', 'type' => 'image', 'group' => 'appearance'],
            
            // System
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'system'],
            ['key' => 'app_timezone', 'value' => 'Asia/Jakarta', 'type' => 'select', 'group' => 'system'],
        ];

        foreach ($settings as $setting) {
             \App\Models\Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
