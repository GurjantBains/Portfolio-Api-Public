<?php
use view\view as view;
//User
Route::get('/projects','ProjectController@projects');
Route::get('/projects/{filter}','ProjectController@filteredProjects');
Route::get('/project/{id}','ProjectController@project');
Route::get('/tags','TagsController@get');
Route::get('/',function(){
    header('content-type: text/html; charset=utf-8');
    view::view('welcome');
});
//Admin
//Auth
Route::post('/admin/login','AdminController@login');
Route::post('/admin/login/token','AdminController@loginToken');
Route::post('/admin/logout','AdminController@logout');
//Projects
Route::get('/admin/projects','AdminController@projects');
Route::get('/admin/project/detail/{id}','AdminController@project');
Route::post('/admin/projects/add','AdminController@addProject');
Route::post('/admin/projects/edit/{id}','AdminController@editProject');
Route::post('/admin/projects/delete','AdminController@deleteProject');
//Tags
Route::post('/admin/tags','AdminController@tags');
Route::post('/admin/tags/add','AdminController@addTag');
Route::post('/admin/tags/edit','AdminController@editTag');
Route::post('/admin/tags/delete','AdminController@deleteTag');
//Messeges
Route::post('/admin/messages','AdminController@messages');
Route::post('/admin/messages/view/{id}','AdminController@viewMessage');
Route::post('/admin/messages/delete','AdminController@deleteMessage');



