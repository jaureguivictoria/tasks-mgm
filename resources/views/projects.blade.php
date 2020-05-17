@extends('layout.base')

@section('content')
    <div class="row">
        <div class="col">

            @if($projects->isNotEmpty())
                <h2>Please choose a project</h2>

                <div class="list-group">
                    @foreach($projects as $project)
                        <a href="{{route('tasks.index', $project)}}" class="list-group-item list-group-item-action">
                            {{$project->name}}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <div class="row mt-5">
        <div class="col">
                <h2>Create a project</h2>

                <form action="{{route('projects.store')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" aria-describedby="name" required>
                        @error('name')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>

        </div>
    </div>
@endsection
