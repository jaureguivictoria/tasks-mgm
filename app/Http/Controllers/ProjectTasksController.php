<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectTaskRequest;
use App\Http\Requests\UpdatePrioritiesRequest;
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
        $project->tasks()->create([
            'name' => $request->get('name'),
            'priority' => $project->tasks()->count()
        ]);

        return view('tasks', ['project' => $project]);
    }

    public function update(UpdateProjectTaskRequest $request, Task $task)
    {
        $task->update($request->only(['name']));

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

    public function updatePriorities(UpdatePrioritiesRequest $request, Project $project)
    {
        $sortedTasksIds = $request->get('sorted_priorities');

        foreach ($sortedTasksIds as $priority => $tasksId) {
            Task::where('id', $tasksId)
                ->update(['priority' => $priority]);
        }

        return response()->json();
    }
}
