<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $module = $request->query('module');
        $action = $request->query('action');
        $search = $request->query('search');

        $query = ActivityLog::query();

        if ($module) {
            $query->where('module', $module);
        }

        if ($action) {
            $query->where('action', $action);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('user_identifier', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(25)->withQueryString();
        $modules = ActivityLog::distinct()->pluck('module')->sort();
        $actions = ActivityLog::distinct()->pluck('action')->sort();

        return view('admin.logs.index', compact('logs', 'modules', 'actions', 'module', 'action', 'search'));
    }
}
