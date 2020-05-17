@extends('layout.base')

@section('content')
    @if($project->tasks->isNotEmpty())
    <div class="row">
        <div class="col">
            <h2>Here are {{$project->name}} tasks</h2>

            <div class="list-group">
                @foreach($project->tasks as $task)
                    <a href="{{route('tasks.view', [$project, $task])}}" class="list-group-item list-group-item-action">
                        {{$task->name}}
                    </a>
                @endforeach
            </div>
        </div>

    </div>
    @endif

    <div class="row mt-5">
        <div class="col">
            <h2>Create a task</h2>

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{route('tasks.store', $project)}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" aria-describedby="name" required>
                </div>
                <div class="form-group">
                    <label for="name">Priority</label>
                    <select class="custom-select @error('priority') is-invalid @enderror" name="priority">
                        <option value="1" selected>One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col">
            <a href="{{route('projects.index')}}" class="btn btn-secondary">Back</a>
        </div>
    </div>
@endsection
