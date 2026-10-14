<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\DailyWorkUpdate;
use Illuminate\Http\Request;

class DailyWorkUpdateController extends Controller
{
    /**
     * Display all daily work updates for managers to review.
     */
    public function index(Request $request)
    {
        $query = DailyWorkUpdate::with(['employee', 'task']);

        if ($request->filled('employee')) {
            $query->where('user_id', $request->input('employee'));
        }

        if ($request->filled('date')) {
            $query->whereDate('work_date', $request->input('date'));
        }

        $updates = $query->latest('work_date')->paginate(15)->withQueryString();
        $employees = \App\Models\User::where('role', 'employee')->orderBy('name')->get();

        return view('manager.work-updates.index', compact('updates', 'employees'));
    }
}
