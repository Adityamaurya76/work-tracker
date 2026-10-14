<?php

namespace Database\Seeders;

use App\Models\DailyWorkUpdate;
use App\Models\PerformanceReview;
use App\Models\Task;
use App\Models\TaskStatusHistories;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create or ensure Manager account
        $manager = User::firstOrCreate(
            ['email' => 'manager@worktrack.com'],
            [
                'name' => 'Sarah Connor',
                'password' => Hash::make('password'),
                'role' => 'manager',
            ]
        );

        // 2. Create or ensure Employee accounts
        $employee1 = User::where('email', 'aditya22@gmail.com')->first();
        if (!$employee1) {
            $employee1 = User::create([
                'name' => 'Aditya Sharma',
                'email' => 'aditya22@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'employee',
            ]);
        }

        $employee2 = User::firstOrCreate(
            ['email' => 'alex@worktrack.com'],
            [
                'name' => 'Alex Morgan',
                'password' => Hash::make('password'),
                'role' => 'employee',
            ]
        );

        // 3. Create Sample Tasks if none exist
        if (Task::count() === 0) {
            $task1 = Task::create([
                'title' => 'Build REST API Authentication',
                'description' => 'Implement Sanctum/JWT token authentication endpoints for the mobile client, including refresh tokens and password reset.',
                'assigned_to' => $employee1->id,
                'assigned_by' => $manager->id,
                'due_date' => now()->addDays(5)->toDateString(),
                'priority' => 'high',
                'status' => 'in_progress',
            ]);

            TaskStatusHistories::create([
                'task_id' => $task1->id,
                'changed_by' => $manager->id,
                'old_status' => null,
                'new_status' => 'todo',
                'comment' => 'Initial task assignment.',
            ]);

            TaskStatusHistories::create([
                'task_id' => $task1->id,
                'changed_by' => $employee1->id,
                'old_status' => 'todo',
                'new_status' => 'in_progress',
                'comment' => 'Started implementation of Sanctum config.',
            ]);

            DailyWorkUpdate::create([
                'user_id' => $employee1->id,
                'task_id' => $task1->id,
                'work_date' => now()->subDay()->toDateString(),
                'work_done' => 'Configured Sanctum tokens, migrations, and user authentication controller methods.',
                'pending_reason' => null,
                'progress_percentage' => 45,
                'status' => 'in_progress',
            ]);

            $task2 = Task::create([
                'title' => 'Database Performance Optimization',
                'description' => 'Add indexes to slow queries identified in production logs. Review query execution plans for daily updates and reports.',
                'assigned_to' => $employee1->id,
                'assigned_by' => $manager->id,
                'due_date' => now()->addDays(2)->toDateString(),
                'priority' => 'medium',
                'status' => 'todo',
            ]);

            $task3 = Task::create([
                'title' => 'Design System UI Components',
                'description' => 'Create reusable Bootstrap 5 Blade component cards, badge utilities, and responsive navigation layouts.',
                'assigned_to' => $employee2->id,
                'assigned_by' => $manager->id,
                'due_date' => now()->subDays(1)->toDateString(),
                'priority' => 'high',
                'status' => 'completed',
                'completed_at' => now()->subHours(6),
            ]);

            TaskStatusHistories::create([
                'task_id' => $task3->id,
                'changed_by' => $employee2->id,
                'old_status' => 'in_progress',
                'new_status' => 'completed',
                'comment' => 'All responsive components merged and tested.',
            ]);

            DailyWorkUpdate::create([
                'user_id' => $employee2->id,
                'task_id' => $task3->id,
                'work_date' => now()->subDays(1)->toDateString(),
                'work_done' => 'Completed all Blade layouts and card components.',
                'pending_reason' => null,
                'progress_percentage' => 100,
                'status' => 'completed',
            ]);

            $task4 = Task::create([
                'title' => 'Third-Party Webhook Integration',
                'description' => 'Integrate payment gateway webhooks and handle idempotent event processing.',
                'assigned_to' => $employee1->id,
                'assigned_by' => $manager->id,
                'due_date' => now()->subDays(2)->toDateString(),
                'priority' => 'high',
                'status' => 'in_progress',
            ]);

            DailyWorkUpdate::create([
                'user_id' => $employee1->id,
                'task_id' => $task4->id,
                'work_date' => now()->toDateString(),
                'work_done' => 'Researched signature verification for incoming payloads.',
                'pending_reason' => 'Waiting for sandbox API credentials from provider.',
                'progress_percentage' => 30,
                'status' => 'blocked',
            ]);
        }

        // 4. Sample Performance Reviews
        if (PerformanceReview::count() === 0) {
            PerformanceReview::create([
                'employee_id' => $employee1->id,
                'reviewer_id' => $manager->id,
                'review_date' => now()->subWeeks(1)->toDateString(),
                'rating' => 5,
                'comments' => 'Aditya demonstrated exceptional problem-solving skills and delivered the authentication and task tracking features ahead of schedule. Great teamwork and clear communication.',
            ]);

            PerformanceReview::create([
                'employee_id' => $employee2->id,
                'reviewer_id' => $manager->id,
                'review_date' => now()->subWeeks(2)->toDateString(),
                'rating' => 4,
                'comments' => 'Solid work on front-end components and responsive layouts. Clean code and good adherence to project guidelines.',
            ]);
        }
    }
}
