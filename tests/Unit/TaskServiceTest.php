<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\TaskService;
use App\Repositories\TaskRepository;
use Mockery;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskServiceTest extends TestCase
{

    use RefreshDatabase;

    public function test_save_task_data()
    {
        $mockRepo = Mockery::mock(TaskRepository::class);
        $mockRepo->shouldReceive('insert')
            ->once()
            ->with([
                "title" => "Test Task",
                "description" => "This is a test task.",
                "due_date" => "2025-02-01",
                "priority" => "high",
            ])
            ->andReturn(true);

        $service = new TaskService($mockRepo);

        $request = new Request([
            "title" => "Test Task",
            "description" => "This is a test task.",
            "due_date" => "2025-02-01",
            "priority" => "high",
        ]);

        $result = $service->saveTaskData($request);

        $this->assertTrue($result);
    }

    public function test_find_task_by_id_success()
    {
        $mockRepo = Mockery::mock(TaskRepository::class);
        $mockRepo->shouldReceive('findFirst')
            ->once()
            ->with(['id' => 1])
            ->andReturn((object) ['id' => 1, 'title' => 'Test Task']);

        $service = new TaskService($mockRepo);

        $result = $service->findTaskById(1);

        $this->assertEquals(1, $result->id);
        $this->assertEquals('Test Task', $result->title);
    }

    public function test_find_task_by_id_not_found()
    {
        $this->expectException(\App\Exceptions\NotFoundException::class);

        $mockRepo = Mockery::mock(TaskRepository::class);
        $mockRepo->shouldReceive('findFirst')
            ->once()
            ->with(['id' => 1])
            ->andReturn(null);

        $service = new TaskService($mockRepo);

        $service->findTaskById(1);
    }

    public function test_update_task_data()
    {
        $mockRepo = Mockery::mock(TaskRepository::class);
        $task = Mockery::mock();
        $task->shouldReceive('update')->once()->with(['title' => 'Updated Title'])->andReturn(true);
        $mockRepo->shouldReceive('findFirst')->twice()->andReturn($task);

        $service = new TaskService($mockRepo);

        $request = new Request(['title' => 'Updated Title']);
        $result = $service->updateTaskData($request, 1);

        $this->assertEquals($task, $result);
    }


    public function test_delete_task_by_id()
    {
        $mockRepo = Mockery::mock(TaskRepository::class);
        $task = Mockery::mock();
        $task->shouldReceive('delete')->once()->andReturn(true);
        $mockRepo->shouldReceive('findFirst')->once()->andReturn($task);

        $service = new TaskService($mockRepo);

        $result = $service->deleteTaskById(1);

        $this->assertTrue($result);
    }

}
