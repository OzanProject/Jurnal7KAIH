<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;

class SetTimezone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $timezone = Setting::get('app_timezone', 'Asia/Jakarta');
        
        config(['app.timezone' => $timezone]);
        date_default_timezone_set($timezone);

        return $next($request);
    }
}
