<?php

namespace Tests\Feature;


use App\Project;
use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class ListTasksTest extends TestCase
{
    use RefreshDatabase;

    public function testCanListTasks()
    {
        $project = factory(Project::class)->create();

        $response = $this->get(route('tasks.index', $project));

        $response->assertOk();

        $response->assertSee('Create a task');
    }

    public function testCanListExistingTasks()
    {
        $project = factory(Project::class)->state('withTasks')->create();

        $this->get(route('tasks.index', $project))
            ->assertOk()
            ->assertSee(sprintf('Here are %s tasks', $project->name));
    }

    public function testCanListTasksOrderedByPriority()
    {
        $project = factory(Project::class)->state('withTasks')->create();

        $this->get(route('tasks.index', $project))
            ->assertOk()
            ->assertSeeTextInOrder($project->tasks()->pluck('name')->toArray());
    }

    public function testCanCreateTask()
    {
        $project = factory(Project::class)->create();

        $params = [
            'name' => 'Prepare for meeting'
        ];

        $this->followingRedirects()
            ->post(route('tasks.store', $project), $params)
            ->assertOk()
            ->assertSee(data_get($params, 'name'));

        $this->assertDatabaseHas('tasks', [
            'name' => data_get($params, 'name'),
            'priority' => 0
        ]);

        $this->assertCount(1, $project->refresh()->tasks);
    }

    public function testCreatedTasksHaveLastPriority()
    {
        $project = factory(Project::class)->state('withTasks')->create();

        $params = [
            'name' => 'Prepare for meeting'
        ];

        $this->followingRedirects()
            ->post(route('tasks.store', $project), $params)
            ->assertOk();

        $this->assertDatabaseHas('tasks', [
            'name' => data_get($params, 'name'),
            'priority' => 3
        ]);

        $this->assertCount(4, $project->refresh()->tasks);
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
    public function testCannotCreateTasks(array $payload, array $expectedErrors)
    {
        $project = factory(Project::class)->create();

        $this->postJson(route('tasks.store', $project), $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors($expectedErrors);

        $this->assertCount(0, Task::all());
    }

}
