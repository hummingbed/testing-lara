<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;


class TaskTest extends TestCase
{
    use RefreshDatabase; // Reset database after each test

    /**
     * Test creating a task.
     *
     * @return void
     */

    public function test_create_task()
    {
        // Create a user to authenticate
        $user = User::factory()->create();

        // Act as the authenticated user
        $this->actingAs($user, 'api');

        // Define the request payload
        $payload = [
            'title' => 'Task 1',
            'description' => 'Finish the project by the end of the week',
            'due_date' => '2025-01-15',
            'priority' => 'high',
        ];

        // Send the POST request
        $response = $this->postJson('/api/tasks', $payload);

        // Assert the response
        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Task created successfully',
                'data' => [
                    'title' => 'Task 1',
                    'description' => 'Finish the project by the end of the week',
                    'due_date' => '2025-01-15',
                    'priority' => 'high',
                ],
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'due_date',
                    'priority',
                ],
            ]);
    }

    public function test_update_task()
    {
        // Create a user to authenticate
        $user = User::factory()->create();

        // Create a task to update
        $task = Task::factory()->create([
            'title' => 'Old Task',
            'description' => 'Old description',
            'due_date' => '2025-01-15',
            'priority' => 'low',
        ]);

        // Act as the authenticated user
        $this->actingAs($user, 'api');

        // Define the update payload
        $updatePayload = [
            'title' => 'Complete project',
            'description' => 'Finish the project by the end of the week',
            'due_date' => '2025-01-19',
            'priority' => 'medium',
        ];

        // Send the PUT request
        $response = $this->putJson("/api/tasks/{$task->id}", $updatePayload);

        // Assert the response
        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Task updated succcessfully',
                'data' => [
                    'id' => $task->id,
                    'title' => 'Complete project',
                    'description' => 'Finish the project by the end of the week',
                    'due_date' => '2025-01-19',
                    'priority' => 'medium',
                ],
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'due_date',
                    'priority',
                ],
            ]);
    }

    public function test_get_single_task_by_id()
    {
        // Simulate a user for authentication
        $user = User::factory()->create();

        // Use a factory to create a task in the test database
        $task = Task::factory()->create([
            'title' => 'hello',
            'description' => 'Finish the project by the end of the week',
            'due_date' => '2025-01-15',
            'priority' => 'high',
        ]);

        // Simulate the authenticated user
        $this->actingAs($user, 'api');

        // Send a GET request to fetch the task by ID
        $response = $this->getJson("/api/tasks/{$task->id}");

        // Assert the response is correct
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task',
                'data' => [
                    'id' => $task->id,
                    'title' => 'hello',
                    'description' => 'Finish the project by the end of the week',
                    'due_date' => '2025-01-15',
                    'priority' => 'high',
                ],
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'title',
                    'description',
                    'due_date',
                    'priority',
                ],
            ]);
    }

    public function testGetAllTasks()
    {
        // Create a mock user
        $user = User::factory()->create();

        // Create mock tasks associated with the user
        $tasks = Task::factory()->count(2)->create([
            'title' => 'hello',
            'description' => 'Finish the project by the end of the week',
            'due_date' => '2025-01-15',
            'priority' => 'high',
        ]);

        // Authenticate as the user
        $response = $this->actingAs($user)
            ->getJson(route('tasks.getAllTask')); // Ensure the route name matches

        // Assert the response
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task retrieved successfully',
                'data' => [
                    [
                        'id' => $tasks[0]->id,
                        'title' => $tasks[0]->title,
                        'description' => $tasks[0]->description,
                        'due_date' => '2025-01-15',
                        'priority' => 'high',
                    ],
                    [
                        'id' => $tasks[1]->id,
                        'title' => $tasks[1]->title,
                        'description' => $tasks[1]->description,
                        'due_date' => '2025-01-15',
                        'priority' => 'high',
                    ],
                ],
            ]);
    }

   /**
     * Test deleting a task by ID.
     */
    public function testDeleteTask()
    {
        // Create a mock user for authentication
        $user = User::factory()->create();

        // Create a mock task
        $task = Task::factory()->create();

        // Authenticate the user
        $response = $this->actingAs($user)
            ->deleteJson(route('tasks.deleteTask', ['id' => $task->id]));

        // Assert the response
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task deleted successfully',
                'data' => true, // Updated to match the actual response
            ]);

        // Assert the task is deleted from the database
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }



}
