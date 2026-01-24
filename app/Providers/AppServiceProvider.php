<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $settings = \App\Models\Setting::all()->pluck('value', 'key');

                if ($settings->get('smtp_host')) {
                    config([
                        'mail.mailers.smtp.host' => $settings->get('smtp_host'),
                        'mail.mailers.smtp.port' => $settings->get('smtp_port'),
                        'mail.mailers.smtp.encryption' => $settings->get('smtp_encryption') === 'null' ? null : $settings->get('smtp_encryption'),
                        'mail.mailers.smtp.username' => $settings->get('smtp_username'),
                        'mail.mailers.smtp.password' => $settings->get('smtp_password'),
                        'mail.from.address' => $settings->get('mail_from_address'),
                        'mail.from.name' => $settings->get('mail_from_name'),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Setup phase, ignore
        }
    }
}
