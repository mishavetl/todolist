@extends('layouts.app')
@section('content')

<div id="task-snippet" style="display: none;">
    @include('task', ['task' => new \App\Task()])        
</div> 

<div id="todolist-snippet" style="display: none">
    @include('todolist', ['todolist' => new \App\Project()])
</div>


<h1 class="text-center">SIMPLE TODO LISTS</h1>
<h3 class="text-center">FROM RUBY GARAGE</h2>
<div id="todolists" class="container text-center">
    @each('todolist', $todolists, 'todolist')
</div>

<div class="text-center">
    <button class="btn btn-primary" id="add-todolist">
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
        Add TODO List
    </button>
</div>

@endsection()