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
                <span class="input-group-btn">                            
                    <button class="btn btn-default task-add-plus-btn" type="button">                                
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </button>
                </span>
                <input type="text" class="form-control" placeholder="Start typing here to create a task">
                <span class="input-group-btn">
                    <button class="btn btn-success task-add-btn" type="button">
                        Add task
                    </button>
                </span>
            </div>
            <div class="tasks">
                <ul class="list-group">
                    @each('task', $todolist->tasks, 'task')
                </ul>
            </div>
        </div>
    </div>
</div>