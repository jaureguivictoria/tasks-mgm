<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectRequest;
use App\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index()
    {
        return view('projects', ['projects' => Project::all()]);
    }

    public function store(CreateProjectRequest $request)
    {
        $project = Project::create($request->only('name'));

        return view('tasks', ['project' => $project]);
    }
}
