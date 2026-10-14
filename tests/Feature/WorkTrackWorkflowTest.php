<?php

namespace Tests\Feature;

use App\Models\DailyWorkUpdate;
use App\Models\PerformanceReview;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkTrackWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $manager;
    protected User $employee1;
    protected User $employee2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = User::firstOrCreate(
            ['email' => 'manager_test@example.com'],
            ['name' => 'Manager Test', 'password' => bcrypt('password'), 'role' => 'manager']
        );

        $this->employee1 = User::firstOrCreate(
            ['email' => 'employee1_test@example.com'],
            ['name' => 'Employee One', 'password' => bcrypt('password'), 'role' => 'employee']
        );

        $this->employee2 = User::firstOrCreate(
            ['email' => 'employee2_test@example.com'],
            ['name' => 'Employee Two', 'password' => bcrypt('password'), 'role' => 'employee']
        );
    }

    public function test_guest_is_redirected_to_login_and_can_view_auth_pages()
    {
        $this->get('/')->assertRedirect('/login');
        $this->get('/login')->assertStatus(200);
        $this->get('/register')->assertStatus(200);
    }

    public function test_authenticated_users_are_redirected_to_their_dashboard_from_home()
    {
        $this->actingAs($this->manager)
            ->get('/')
            ->assertRedirect('/dashboard');
        $this->get('/dashboard')->assertRedirect('/manager/dashboard');

        $this->actingAs($this->employee1)
            ->get('/')
            ->assertRedirect('/dashboard');
        $this->get('/dashboard')->assertRedirect('/employee/dashboard');
    }

    public function test_manager_can_access_dashboard_and_modules()
    {
        $this->actingAs($this->manager);

        // Dashboard
        $this->get('/manager/dashboard')->assertStatus(200);

        // Tasks
        $this->get('/manager/tasks')->assertStatus(200);
        $this->get('/manager/tasks/create')->assertStatus(200);

        // Work Updates
        $this->get('/manager/work-updates')->assertStatus(200);

        // Reviews
        $this->get('/manager/reviews')->assertStatus(200);
        $this->get('/manager/reviews/create')->assertStatus(200);
    }

    public function test_employee_can_access_dashboard_and_modules()
    {
        $this->actingAs($this->employee1);

        $this->get('/employee/dashboard')->assertStatus(200);
        $this->get('/employee/tasks')->assertStatus(200);
        $this->get('/employee/work-updates')->assertStatus(200);
        $this->get('/employee/work-updates/create')->assertStatus(200);
        $this->get('/employee/reviews')->assertStatus(200);
    }

    public function test_employee_cannot_access_manager_routes()
    {
        $this->actingAs($this->employee1);

        $this->get('/manager/dashboard')->assertStatus(403);
        $this->get('/manager/tasks')->assertStatus(403);
        $this->get('/manager/work-updates')->assertStatus(403);
        $this->get('/manager/reviews')->assertStatus(403);
    }

    public function test_manager_cannot_access_employee_routes()
    {
        $this->actingAs($this->manager);

        $this->get('/employee/dashboard')->assertStatus(403);
        $this->get('/employee/tasks')->assertStatus(403);
    }

    public function test_manager_can_create_task()
    {
        $this->actingAs($this->manager);

        $response = $this->post('/manager/tasks', [
            'title' => 'Automated Test Task',
            'description' => 'Test description here',
            'assigned_to' => $this->employee1->id,
            'priority' => 'high',
            'due_date' => now()->addDays(3)->toDateString(),
        ]);

        $response->assertRedirect('/manager/tasks');
        $this->assertDatabaseHas('tasks', ['title' => 'Automated Test Task']);
    }

    public function test_employee_can_update_task_status()
    {
        $task = Task::create([
            'title' => 'Status Update Test Task',
            'description' => 'Testing status update',
            'assigned_to' => $this->employee1->id,
            'assigned_by' => $this->manager->id,
            'priority' => 'medium',
            'status' => 'todo',
            'due_date' => now()->addDays(2)->toDateString(),
        ]);

        $this->actingAs($this->employee1);

        $response = $this->patch("/employee/tasks/{$task->id}/status", [
            'status' => 'in_progress',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'in_progress',
        ]);
        $this->assertDatabaseHas('task_status_histories', [
            'task_id' => $task->id,
            'old_status' => 'todo',
            'new_status' => 'in_progress',
        ]);
    }

    public function test_employee_cannot_view_or_update_other_employee_task()
    {
        $task = Task::create([
            'title' => 'Other Employee Task',
            'description' => 'Testing unauthorized access',
            'assigned_to' => $this->employee2->id,
            'assigned_by' => $this->manager->id,
            'priority' => 'medium',
            'status' => 'todo',
        ]);

        $this->actingAs($this->employee1);

        $this->get("/employee/tasks/{$task->id}")->assertStatus(403);
        $this->patch("/employee/tasks/{$task->id}/status", ['status' => 'in_progress'])->assertStatus(403);
    }

    public function test_employee_can_submit_daily_work_update()
    {
        $task = Task::create([
            'title' => 'Daily Update Task',
            'assigned_to' => $this->employee1->id,
            'assigned_by' => $this->manager->id,
            'priority' => 'low',
            'status' => 'in_progress',
        ]);

        $this->actingAs($this->employee1);

        $response = $this->post('/employee/work-updates', [
            'task_id' => $task->id,
            'work_date' => now()->toDateString(),
            'work_done' => 'Worked on API endpoints today.',
            'pending_reason' => 'None',
            'progress_percentage' => 75,
            'status' => 'in_progress',
        ]);

        $response->assertRedirect('/employee/work-updates');
        $this->assertDatabaseHas('daily_work_updates', [
            'task_id' => $task->id,
            'user_id' => $this->employee1->id,
            'progress_percentage' => 75,
        ]);
    }
}
