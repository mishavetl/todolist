<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Middleware\CheckLastAction;
use App\Project;

class ProjectsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', CheckLastAction::class]);
    }

    private static $validationRules = [
        'name' => 'required|max:255'
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
        
        $project = Project::create([
            'name' => request('name'),
            'user_id' => auth()->id()
        ]);
        
        return response($project, 201);
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
        $project = Project::findOrFail($id);
        if ($project->user->id != auth()->id()) {
            return response('Forbidden', 403);
        }
        $project->name = request('name');
        $project->save();

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
        $project = Project::findOrFail($id);
        if ($project->user->id != auth()->id()) {
            return response('Forbidden', 403);
        }
        $project->delete();
        return response(null, 204);
    }
}
