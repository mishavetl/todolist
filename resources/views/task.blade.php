<li class="list-group-item task-list-item task" data-id="{{ $task->id }}" data-todolist-id="{{ $task->project_id }}">
    <div class="row">
        <div class="col-md-1">
            <input type="checkbox" class="task-status" {{ $task->status == 1 ? 'checked' : '' }} />
        </div>
        <div class="col-md-6">
            <p class="text-left task-name">{{ $task->name }}</p>
            <input style="display: none;"
                class="form-control task-name-edit" value="{{ $task->name }}" />
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <div class='input-group date task-deadline'>
                    <input type='text' class="form-control task-deadline-field" value="{{ $task->deadline }}" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-1 modify-options">
            <span class="glyphicon glyphicon-pencil task-update" aria-hidden="true"></span>
            <span class="glyphicon glyphicon-trash task-delete" aria-hidden="true"></span>
        </div>
    </div>
</li>