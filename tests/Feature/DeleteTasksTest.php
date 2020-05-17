<?php

namespace Tests\Feature;


use App\Project;
use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class DeleteTasksTest extends TestCase
{
    use RefreshDatabase;

    public function testCanDeleteTasks()
    {
        $task = factory(Task::class)->create([
            'name' => 'Sprint review',
            'priority' => 0,
        ]);

        $this->followingRedirects()
            ->delete(route('tasks.destroy', $task))
            ->assertOk()
            ->assertDontSeeText($task->name);

        $this->assertDatabaseMissing('tasks', [
            'name' => $task->name,
            'priority' => $task->priority
        ]);

    }

    public function testCannotDeleteNonExistentTasks()
    {
        $this->delete(route('tasks.destroy', 'fakeId'))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

}
