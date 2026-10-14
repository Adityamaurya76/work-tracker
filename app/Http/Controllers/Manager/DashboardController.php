<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = Task::query()
            ->selectRaw("COUNT(*) as total")
            ->selectRaw("SUM(CASE WHEN assigned_to IS NOT NULL THEN 1 ELSE 0 END) as assigned")
            ->selectRaw("SUM(CASE WHEN status = 'todo' THEN 1 ELSE 0 END) as pending")
            ->selectRaw("SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress")
            ->selectRaw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed")
            ->selectRaw("SUM(CASE WHEN status != 'completed' AND due_date < ? THEN 1 ELSE 0 END) as overdue", [now()->toDateString()])
            ->first();

        $totalEmployees = User::where('role', 'employee')->count();

        $recentTasks = Task::with(['employee', 'assigner'])
            ->latest()
            ->take(10)
            ->get();

        return view('manager.dashboard', compact('stats', 'totalEmployees', 'recentTasks'));
    }
}
