<li class="list-group-item task-list-item task" data-id="{{ $task->id }}" data-todolist-id="{{ $task->project_id }}">
    <div class="row">
        <div class="col-md-1">
            <input type="checkbox" class="task-status" {{ $task->status == 1 ? 'checked' : '' }} />
        </div>
        <div class="col-md-10">
            <p class="text-left task-name">{{ $task->name }}</p>
            <input style="display: none;"
                class="form-control task-name-edit" value="{{ $task->name }}" />
        </div>
        <div class="col-md-1 modify-options">
            <span class="glyphicon glyphicon-pencil task-update" aria-hidden="true"></span>
            <span class="glyphicon glyphicon-trash task-delete" aria-hidden="true"></span>
        </div>
    </div>
</li>