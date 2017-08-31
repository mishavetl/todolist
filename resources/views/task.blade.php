<li class="list-group-item task-list-item">
    <div class="row task" data-id="{{ $task->id }}">
        <div class="col-md-1 task-done">
            <input type="checkbox" />
        </div>
        <div class="col-md-10">
            <p class="text-left task-name">{{ $task->name }}</p>
        </div>
        <div class="col-md-1 modify-options">
            <span class="glyphicon glyphicon-pencil task-update" aria-hidden="true"></span>
            <span class="glyphicon glyphicon-trash task-delete" aria-hidden="true"></span>
        </div>
    </div>
</li>