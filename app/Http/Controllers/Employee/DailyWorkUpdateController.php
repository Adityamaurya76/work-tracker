<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\DailyWorkUpdate;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyWorkUpdateController extends Controller
{
    /**
     * Display the employee's daily work updates.
     */
    public function index(Request $request)
    {
        $query = DailyWorkUpdate::with('task')
            ->where('user_id', Auth::id());

        if ($request->filled('task_id')) {
            $query->where('task_id', $request->input('task_id'));
        }

        $updates = $query->latest('work_date')->paginate(10)->withQueryString();

        $tasks = Task::where('assigned_to', Auth::id())
            ->orderBy('title')
            ->get(['id', 'title']);

        return view('employee.work-updates.index', compact('updates', 'tasks'));
    }

    /**
     * Show the form for creating a new daily work update.
     */
    public function create()
    {
        $tasks = Task::where('assigned_to', Auth::id())
            ->whereIn('status', ['todo', 'in_progress'])
            ->orderBy('title')
            ->get(['id', 'title']);

        return view('employee.work-updates.create', compact('tasks'));
    }

    /**
     * Store a newly created daily work update.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_id' => ['required', 'integer', 'exists:tasks,id'],
            'work_date' => ['required', 'date', 'before_or_equal:today'],
            'work_done' => ['required', 'string', 'max:5000'],
            'pending_reason' => ['nullable', 'string', 'max:5000'],
            'progress_percentage' => ['required', 'integer', 'min:0', 'max:100'],
            'status' => ['required', 'in:in_progress,completed,blocked'],
        ]);

        $task = Task::findOrFail($validated['task_id']);

        if ($task->assigned_to !== Auth::id()) {
            abort(403, 'You can only submit updates for tasks assigned to you.');
        }

        $validated['user_id'] = Auth::id();

        DailyWorkUpdate::create($validated);

        return redirect()
            ->route('employee.work-updates.index')
            ->with('success', 'Daily work update submitted successfully.');
    }

    /**
     * Display the specified daily work update.
     */
    public function show(DailyWorkUpdate $workUpdate)
    {
        if ($workUpdate->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to view this update.');
        }

        $workUpdate->load('task');

        return view('employee.work-updates.show', compact('workUpdate'));
    }
}
