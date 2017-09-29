<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\CheckLastAction;
use App\Task;

class TasksController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', CheckLastAction::class]);
    }

    private static $validationRules = [
        'name' => 'required|max:255',
        'status' => 'required|boolean',
        'project_id' => 'required|exists:projects,id',
        'deadline' => 'nullable|date_format:Y-m-d H:i:s'
    ];

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, self::$validationRules);
        
        $task = Task::create([
            'name' => request('name'),
            'status' => request('status'),
            'project_id' => request('project_id'),
            'deadline' => request('deadline')
        ]);
        
        return response($task, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, self::$validationRules);
        $task = Task::findOrFail($id);
        if ($task->project->user->id != auth()->id()) {
            return response('Forbidden', 403);
        }
        $task->name = request('name');
        $task->status = request('status');
        $task->project_id = request('project_id');
        $task->deadline = request('deadline');
        $task->save();

        return response('Updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        if ($task->project->user->id != auth()->id()) {
            return response('Forbidden', 403);
        }
        $task->delete();
        return response(null, 204);
    }
}
