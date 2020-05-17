@extends('layout.base')

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        $( function() {
            $( "#sortable" ).sortable({
                revert: true,
                stop: function( event, ui ) {
                    const sortedPriorities = $( "#sortable" ).sortable("toArray");

                    console.log(sortedPriorities);
                    $.ajax({
                        type: "PUT",
                        dataType: "JSON",
                        url: "{{route('tasks.sort')}}",
                        data: {
                            "sorted_priorities": sortedPriorities,
                            "_token": "{{csrf_token()}}",
                        },
                        success: function(e) {
                            alert('Priority updated');
                        }
                    })
                }
            });
        } );
    </script>
@endsection


@section('content')
    @if($project->tasks->isNotEmpty())
    <div class="row">
        <div class="col">
            <h2>Here are {{$project->name}} tasks</h2>

            <div class="list-group" id="sortable">
                @foreach($project->tasks as $task)
                    <a href="{{route('tasks.view', [$project, $task])}}" id="{{$task->id}}" class="list-group-item list-group-item-action">
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
