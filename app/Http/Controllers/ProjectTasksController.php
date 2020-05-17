<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectTaskRequest;
use App\Http\Requests\UpdateProjectTaskRequest;
use App\Project;
use App\Task;


class ProjectTasksController extends Controller
{
    public function index(Project $project)
    {
        return view('tasks', ['project' => $project]);
    }

    public function store(CreateProjectTaskRequest $request, Project $project)
    {
        $project->tasks()->create($request->only(['name', 'priority']));

        return view('tasks', ['project' => $project]);
    }

    public function update(UpdateProjectTaskRequest $request, Task $task)
    {
        $task->update($request->only(['name', 'priority']));

        return redirect()->route('tasks.index', ['project' => $task->project]);
    }

    public function view(Project $project, Task $task)
    {
        return view('task', ['task' => $task]);
    }

    public function destroy(Task $task)
    {
        $project = $task->project;

        $task->delete();

        return redirect()->route('tasks.index', ['project' => $project]);
    }
}
