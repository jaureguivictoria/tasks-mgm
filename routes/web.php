<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'ProjectsController@index')->name('projects.index');
Route::post('projects', 'ProjectsController@store')->name('projects.store');
Route::get('projects/{project}/tasks', 'ProjectTasksController@index')->name('tasks.index');
Route::post('projects/{project}/tasks', 'ProjectTasksController@store')->name('tasks.store');
Route::patch('tasks/{task}', 'ProjectTasksController@update')->name('tasks.update');
Route::get('projects/{project}/tasks/{task}', 'ProjectTasksController@view')->name('tasks.view');
Route::delete('tasks/{task}', 'ProjectTasksController@destroy')->name('tasks.destroy');
