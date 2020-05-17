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

    public function testCanBulkUpdateTasksPriority()
    {
        $project = factory(Project::class)->state('withTasks')->create();

        $first = $project->tasks[0];
        $second = $project->tasks[1];
        $third = $project->tasks[2];

        $payload = [
            'sorted_priorities' => [
                $third->id,
                $first->id,
                $second->id
            ],
        ];

        $this->putJson(route('tasks.sort', $project), $payload)
            ->assertOk();

        $this->assertDatabaseHas('tasks', [
            'id' => $third->id,
            'priority' => 0
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $first->id,
            'priority' => 1
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $second->id,
            'priority' => 2
        ]);
    }


    public function invalidTaskPrioritiesPayload(): array
    {
        return [
            'sorted_priorities is required' => [
                [],
                ['sorted_priorities']
            ],
            'sorted_priorities must be an array' => [
                ['sorted_priorities' => '1'],
                ['sorted_priorities']
            ],
            'sorted_priorities must be contain numbers' => [
                ['sorted_priorities' => ['a']],
                ['sorted_priorities.0']
            ],
            'sorted_priorities cannot be less than 0' => [
                ['sorted_priorities' => [-1]],
                ['sorted_priorities.0']
            ],
        ];
    }

    /**
     * @dataProvider invalidTaskPrioritiesPayload
     */
    public function testCannotBulkUpdateTasksPriorities(array $payload, array $expectedErrors)
    {
        $project = factory(Project::class)->state('withTasks')->create();

        $this->putJson(route('tasks.sort', $project), $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors($expectedErrors);
    }

}
