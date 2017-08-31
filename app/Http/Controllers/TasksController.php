<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    private static $validationRules = [
        'name' => 'required|max:255',
        'status' => 'required|boolean',
        'project_id' => 'required|exists:projects,id'
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
            'project_id' => request('project_id')
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
            return response("Forbidden", 403);
        }
        $task->name = request('name');
        $task->status = request('status');
        $task->project_id = request('project_id');
        $task->save();

        return response("Updated", 200);
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
            return response("Forbidden", 403);
        }
        $task->delete();
        return response(null, 204);
    }
}
