<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskStatusHistories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Display the employee's assigned tasks with filters.
     */
    public function index(Request $request)
    {
        $query = Task::with('assigner')
            ->where('assigned_to', Auth::id());

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        $tasks = $query->latest()->paginate(10)->withQueryString();

        return view('employee.tasks.index', compact('tasks'));
    }

    /**
     * Display the specified task (only if assigned to this employee).
     */
    public function show(Task $task)
    {
        if ($task->assigned_to !== Auth::id()) {
            abort(403, 'You are not authorized to view this task.');
        }

        $task->load(['assigner', 'statusHistories.changedBy', 'workUpdates']);

        return view('employee.tasks.show', compact('task'));
    }

    /**
     * Update the task status.
     */
    public function updateStatus(Request $request, Task $task)
    {
        if ($task->assigned_to !== Auth::id()) {
            abort(403, 'You are not authorized to update this task.');
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['todo', 'in_progress', 'completed'])],
        ]);

        $oldStatus = $task->status;
        $newStatus = $validated['status'];

        if ($oldStatus === $newStatus) {
            return back()->with('info', 'Task status is already set to ' . str_replace('_', ' ', $newStatus) . '.');
        }

        DB::transaction(function () use ($task, $oldStatus, $newStatus) {
            $updateData = ['status' => $newStatus];

            if ($newStatus === 'completed') {
                $updateData['completed_at'] = now();
            } elseif ($oldStatus === 'completed') {
                $updateData['completed_at'] = null;
            }

            $task->update($updateData);

            TaskStatusHistories::create([
                'task_id' => $task->id,
                'changed_by' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'comment' => 'Status updated by employee.',
            ]);
        });

        return back()->with('success', 'Task status updated to ' . str_replace('_', ' ', $newStatus) . '.');
    }
}
