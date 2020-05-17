@extends('layout.base')

@section('content')
    <div class="row">
        <div class="col">
            <h2>Edit the {{$task->project->name}} task</h2>

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{route('tasks.update', $task)}}" method="POST">
                @method('PATCH')
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" aria-describedby="name" required value="{{$task->name}}">
                </div>
                <div class="form-group">
                    <label for="name">Priority</label>
                    <select class="custom-select @error('priority') is-invalid @enderror" name="priority">
                        <option value="1" {{ $task->priority == 1 ? 'selected' : '' }}>One</option>
                        <option value="2" {{ $task->priority == 2 ? 'selected' : '' }}>Two</option>
                        <option value="3" {{ $task->priority == 3 ? 'selected' : '' }}>Three</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{route('tasks.index', $task->project)}}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col">
            <form action="{{route('tasks.destroy', $task)}}" method="POST">
                @method('DELETE')
                @csrf
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>

        </div>
    </div>
@endsection
