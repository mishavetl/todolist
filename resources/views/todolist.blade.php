<div class="todolist" id="todolist-{{ $todolist->id }}" data-id="{{ $todolist->id }}">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-1">
                    <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                </div>
                <div class="col-md-10">
                    <h3 class="panel-title text-left todolist-name">{{ $todolist->name }}</h3>
                    <input style="display: none;"
                        class="form-control todolist-name-edit" value="{{ $todolist->name }}" />
                </div>
                <div class="col-md-1 modify-options">
                    <span class="glyphicon glyphicon-pencil todolist-update" aria-hidden="true"></span>
                    <span class="glyphicon glyphicon-trash todolist-delete" aria-hidden="true"></span>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="input-group task-manager">
                <span class="input-group-addon" id="add-task-plus-addon">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </span>
                <input type="text" class="form-control new-task-name"
                    aria-describedby="add-task-plus-addon" placeholder="Start typing here to create a task">
                <span class="input-group-btn">
                    <button class="btn btn-success task-add-btn add-task" type="button">
                        Add task
                    </button>
                </span>
            </div>
            <div class="">
                <ul class="list-group tasks">
                    @each('task', $todolist->tasks, 'task')
                </ul>
            </div>
        </div>
    </div>
</div>