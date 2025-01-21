<?php


namespace Tests\Feature;

use Tests\TestCase;
use App\Services\TaskService;
use App\Helpers\ResponseMessages;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_task()
    {
        $mockService = Mockery::mock(TaskService::class);
        $mockService->shouldReceive('saveTaskData')
            ->once()
            ->andReturn(['id' => 1, 'title' => 'Test Task']);

        $this->app->instance(TaskService::class, $mockService);

        $response = $this->postJson('/api/tasks', [
            'title' => 'Test Task',
            'description' => 'This is a test task.',
            'due_date' => '2025-02-01',
            'priority' => 'high',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => ResponseMessages::getSuccessMessage('Task created successfully'),
                'data' => ['id' => 1, 'title' => 'Test Task'],
            ]);
    }

    public function test_update_task()
    {
        $mockService = Mockery::mock(TaskService::class);
        $mockService->shouldReceive('updateTaskData')
            ->once()
            ->with(Mockery::type('Illuminate\Http\Request'), 1)
            ->andReturn(['id' => 1, 'title' => 'Updated Task']);

        $this->app->instance(TaskService::class, $mockService);

        $response = $this->putJson('/api/tasks/1', [
            'title' => 'Updated Task',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => ResponseMessages::getSuccessMessage('Task updated succcessfully'),
                'data' => ['id' => 1, 'title' => 'Updated Task'],
            ]);
    }


    public function test_get_single_task_by_id()
    {
        $mockService = Mockery::mock(TaskService::class);
        $mockService->shouldReceive('findTaskById')
            ->once()
            ->with(1)
            ->andReturn((object) [
                'id' => 1,
                'title' => 'Test Task',
                'description' => 'This is a test task description',  // Added description
                'due_date' => '2025-02-01',
                'priority' => 'high'
            ]);

        $this->app->instance(TaskService::class, $mockService);

        $response = $this->getJson('/api/tasks/1');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => ResponseMessages::getSuccessMessage('Task'),
                'data' => [
                    'id' => 1,
                    'title' => 'Test Task',
                    'description' => 'This is a test task description',  
                    'due_date' => '2025-02-01',
                    'priority' => 'high',
                ],
            ]);
    }


    public function test_get_all_tasks()
    {
        $mockService = Mockery::mock(TaskService::class);
        $mockService->shouldReceive('queryTask')
            ->once()
            ->andReturn([
                (object) [
                    'id' => 1,
                    'title' => 'Task 1',
                    'description' => 'Description for Task 1',
                    'due_date' => '2025-02-01',
                    'priority' => 'high'
                ],
                (object) [
                    'id' => 2,
                    'title' => 'Task 2',
                    'description' => 'Description for Task 2',
                    'due_date' => '2025-02-02',
                    'priority' => 'low'
                ],
            ]);

        $this->app->instance(TaskService::class, $mockService);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => ResponseMessages::getSuccessMessage('Task retrieved successfully'),
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'description', 'due_date', 'priority'],  
                ],
            ]);
    }


    public function test_delete_task()
    {
        $mockService = Mockery::mock(TaskService::class);
        $mockService->shouldReceive('deleteTaskById')
            ->once()
            ->with(1)
            ->andReturn(true);

        $this->app->instance(TaskService::class, $mockService);

        $response = $this->deleteJson('/api/tasks/1');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => ResponseMessages::getSuccessMessage('Task deleted successfully'),
                'data' => true,
            ]);
    }

}
