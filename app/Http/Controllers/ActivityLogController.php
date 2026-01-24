<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = \App\Models\ActivityLog::with('user')->latest()->paginate(20);
        return view('backend.superadmin.activity_logs.index', compact('logs'));
    }

    public function destroyAll()
    {
        \App\Helpers\ActivityLogger::log('Clear Logs', 'User cleared all activity logs');
        \App\Models\ActivityLog::truncate();
        return back()->with('success', 'Seluruh log aktivitas berhasil dihapus.');
    }
}
