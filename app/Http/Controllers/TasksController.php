<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\CheckLastAction;
use App\Task;
use App\Project;

class TasksController extends Controller
{
    const priorityMax = 9000000000000;
    const priorityMin = -9000000000001;

    const PRIORITY_UP = 1;
    const PRIORITY_DOWN = -1;

    private static $validationRules = [
        'name' => 'required|max:255',
        'status' => 'required|boolean',
        'project_id' => 'required|exists:projects,id',
        'deadline' => 'nullable|date_format:Y-m-d H:i:s'
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('App\Http\Middleware\CheckLastAction')->except('optimize');
        $this->middleware('App\Http\Middleware\CheckLastAction:true')->only('optimize');
    }

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
            'deadline' => request('deadline'),
            'priority' => getPriority(
                [],
                Project::findOrFail(request('project_id'))->tasks()->orderBy('priority')->get(),
                self::priorityMin, self::priorityMax),
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function priority(Request $request, $id)
    {
        $this->validate($request, [
            'priority' => 'in:' . self::PRIORITY_UP . ',' . self::PRIORITY_DOWN
        ]);
        
        $task = Task::findOrFail($id);

        $after = []; $before = [];
        if (request('priority') == self::PRIORITY_UP) {
            $after = $task->project->tasks()->where('priority', '>', $task->priority)
                ->orderBy('priority');
            $count = $after->count();
            $after = $after->limit($after->count())->offset(1)->get();
            $before = $task->project->tasks()->where(function ($query) use ($count, $after) {
                if ($count > 1) $query->where('priority', '<', $after[0]->priority);
            })->where('priority', '<>', $task->priority)->orderBy('priority')->get();
            // var_dump($after);
            // var_dump($before);
        } else if (request('priority') == self::PRIORITY_DOWN) {
            $before = $task->project->tasks()->where('priority', '<', $task->priority)->orderBy('priority');
            $count = $before->count();
            $before = $before->limit($count - 1)->get();
            $after = $task->project->tasks()->where(function ($query) use ($count, $before) {
                if ($count > 2) $query->where('priority', '>', $before[$count - 2]->priority);
            })->where('priority', '<>', $task->priority)->orderBy('priority')->get();
        }
        $task->priority = getPriority($before, $after,
            self::priorityMin, self::priorityMax);

        $task->save();
        
        return response('Updated', 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function optimize()
    {
        foreach (auth()->user()->projects()->get() as $project) {
            optimizePriority($project->tasks()->orderBy('priority')->get(),
                self::priorityMin, self::priorityMax);
        }

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
