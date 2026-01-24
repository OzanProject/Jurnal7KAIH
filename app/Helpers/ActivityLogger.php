<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log($action, $description = null, $subject = null)
    {
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'description' => $description,
                'subject_type' => $subject ? get_class($subject) : null,
                'subject_id' => $subject ? $subject->id : null,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        }
    }
}
