<?php

namespace Tests\Feature;

use App\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCanListProjects()
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertSee('Create a project');

        $response->assertDontSeeText('Please choose a project');
    }

    public function testCanListExistingProjects()
    {
        $project = factory(Project::class)->create();

        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertSee('Please choose a project');

        $response->assertSee($project->name);
    }

    public function testCanCreateProjects()
    {
        $params = [
            'name' => 'Acme'
        ];
        $this->post(route('projects.store'), $params)
            ->assertOk()
            ->assertSee('Create a task');

        $this->assertDatabaseHas('projects', [
            'name' => data_get($params, 'name')
        ]);
    }

    public function invalidProjectPayload(): array
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
     * @dataProvider invalidProjectPayload
     */
    public function testCannotCreateProjects(array $payload, array $expectedErrors)
    {
        $this->postJson(route('projects.store'), $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors($expectedErrors);

        $this->assertCount(0, Project::all());
    }
}
