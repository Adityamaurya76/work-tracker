<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskStatusHistories;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks with search and filters.
     */
    public function index(Request $request)
    {
        $query = Task::with(['employee', 'assigner']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        if ($request->filled('employee')) {
            $query->where('assigned_to', $request->input('employee'));
        }

        $tasks = $query->latest()->paginate(10)->withQueryString();

        $employees = User::where('role', 'employee')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('manager.tasks.index', compact('tasks', 'employees'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        $employees = User::where('role', 'employee')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('manager.tasks.create', compact('employees'));
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'assigned_to' => [
                'required',
                'integer',
                Rule::exists('users', 'id')
                    ->where(fn ($query) => $query->where('role', 'employee')),
            ],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
        ]);

        $validated['assigned_by'] = Auth::id();
        $validated['status'] = 'todo';

        DB::transaction(function () use ($validated) {
            $task = Task::create($validated);

            TaskStatusHistories::create([
                'task_id' => $task->id,
                'changed_by' => Auth::id(),
                'old_status' => null,
                'new_status' => 'todo',
                'comment' => 'Task created and assigned.',
            ]);
        });

        return redirect()
            ->route('manager.tasks.index')
            ->with('success', 'Task successfully assigned to employee.');
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        $task->load(['employee', 'assigner', 'statusHistories.changedBy', 'workUpdates.employee']);

        return view('manager.tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task)
    {
        $employees = User::where('role', 'employee')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('manager.tasks.edit', compact('task', 'employees'));
    }

    /**
     * Update the specified task.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'assigned_to' => [
                'required',
                'integer',
                Rule::exists('users', 'id')
                    ->where(fn ($query) => $query->where('role', 'employee')),
            ],
            'due_date' => ['required', 'date'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'status' => ['required', Rule::in(['todo', 'in_progress', 'completed'])],
        ]);

        DB::transaction(function () use ($task, $validated) {
            $oldStatus = $task->status;

            if ($validated['status'] === 'completed' && $oldStatus !== 'completed') {
                $validated['completed_at'] = now();
            } elseif ($validated['status'] !== 'completed' && $oldStatus === 'completed') {
                $validated['completed_at'] = null;
            }

            $task->update($validated);

            if ($oldStatus !== $validated['status']) {
                TaskStatusHistories::create([
                    'task_id' => $task->id,
                    'changed_by' => Auth::id(),
                    'old_status' => $oldStatus,
                    'new_status' => $validated['status'],
                    'comment' => 'Status updated by manager.',
                ]);
            }
        });

        return redirect()
            ->route('manager.tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified task.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()
            ->route('manager.tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}