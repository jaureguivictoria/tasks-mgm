<?php

namespace Tests\Feature;


use App\Project;
use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpdateTasksTest extends TestCase
{
    use RefreshDatabase;

    public function testCanUpdateTasks()
    {
        $task = factory(Task::class)->create([
            'name' => 'Sprint review',
            'priority' => 0,
        ]);

        $params = [
            'name' => 'Prepare meeting',
        ];

        $this->followingRedirects()
            ->patch(route('tasks.update', $task), $params)
            ->assertOk()
            ->assertSee(data_get($params, 'name'));

        $this->assertDatabaseHas('tasks', [
            'name' => data_get($params, 'name'),
            'priority' => 0
        ]);

    }

    public function invalidTaskPayload(): array
    {
        return [
            'name is required' => [
                [],
                ['name']
            ],
            'name should be a string' => [
                ['name' => 1234],
                ['name']
            ],
            'name should be a less than 255 characters' => [
                ['name' => Str::random(256)],
                ['name']
            ],

        ];
    }

    /**
     * @dataProvider invalidTaskPayload
     */
    public function testCannotUpdateTasks(array $payload, array $expectedErrors)
    {
        $task = factory(Task::class)->create();

        $this->patchJson(route('tasks.update', $task), $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors($expectedErrors);

        $this->assertDatabaseHas('tasks', [
            'name' => $task->name,
            'priority' => $task->priority
        ]);
    }

}
