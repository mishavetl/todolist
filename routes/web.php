<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Project;

Route::get('/', function () {
    // $todolists = Project::all();
    $todolists = auth()->user()->projects;
    return view('index', compact('todolists'));
})->middleware('auth');

Route::resource('/todolists', 'ProjectsController', [
    'only' => ['store', 'update', 'destroy'] 
]);

Route::resource('/tasks', 'TasksController', [
    'only' => ['store', 'update', 'destroy'] 
]);

Auth::routes();
