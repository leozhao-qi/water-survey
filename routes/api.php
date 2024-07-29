<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::prefix('users')->group(function () {
    Route::get('deactivations', 'Users\Api\UsersController@inactiveIndex');
    Route::get('{user}', 'Users\Api\UsersController@show');
    Route::post('{user}/role/{role}', 'Supervisors\Api\SupervisorsController@store');
    Route::post('{user}/packages', 'Packages\Api\PackageController@store');
    Route::put('{user}/password', 'Users\Api\PasswordChangeController@update');
    Route::put('{user}/appointment', 'Users\Api\AppointmentDateController@update');
    Route::put('{user}/role', 'Users\Api\RoleChangeController@update');
    Route::get('/', 'Users\Api\UsersController@index');
    Route::delete('{user}', 'Users\Api\UsersController@destroy');
    Route::get('{user}/packages/{package}', 'Packages\Api\PackageController@show');
    Route::put('{user}/packages', 'Packages\Api\PackageController@add');
    Route::get('{user}/packages', 'Logbooks\Api\LogbookPackageController@index');
    Route::put('{user}/packages/{package}', 'Packages\Api\PackageController@update');
    Route::post('create', 'Users\Api\UsersController@store');
});

Route::prefix('topics')->group(function () {
    Route::get('/', 'Topics\Api\TopicController@index');
    Route::post('/', 'Topics\Api\TopicController@store');
    Route::put('{topic}', 'Topics\Api\TopicController@update');
    Route::delete('{topic}', 'Topics\Api\TopicController@destroy');
});

Route::prefix('reports')->group(function () {
   Route::get('rot', 'Rot\Api\RotController@index');
   Route::get('rot/download', 'Rot\Api\RotController@download');
});

Route::resource('logbooks', 'Logbooks\Api\LogbookController');
Route::prefix('logbooks')->group(function () {
    Route::post('files/upload', 'Logbooks\Api\LogbookFilesController@upload');
    Route::post('filemeta', 'Logbooks\Api\LogbookFilesController@meta');
    Route::get('{logbook}/comments', 'Logbooks\Api\LogbookCommentController@index');
    Route::post('{logbook}/comments', 'Logbooks\Api\LogbookCommentController@store');
    Route::put('{logbook}/comments/{comment}', 'Logbooks\Api\LogbookCommentController@update');
    Route::delete('{logbook}/comments/{comment}', 'Logbooks\Api\LogbookCommentController@destroy');
});

Route::prefix('files')->group(function () {
    Route::put('{file}', 'Logbooks\Api\LogbookFilesController@updateFilename');
    Route::delete('{file}', 'Logbooks\Api\LogbookFilesController@destroy');
});

Route::prefix('roles')->group(function () {
   Route::get('/', 'Roles\Api\RolesController@index');
});

Route::get('supervisors/{role}', 'Supervisors\Api\SupervisorsController@index');
Route::get('packages/{package}/objectives', 'Logbooks\Api\LogbookPackageController@objectives');

Route::prefix('deactivations')->group(function () {
    Route::put('{deactivation}', 'Deactivations\Api\DeactivationController@update');
    Route::post('{user}', 'Deactivations\Api\DeactivationController@store');
    Route::put('{user}/activate', 'Deactivations\Api\DeactivationController@activate');
    Route::delete('{deactivation}', 'Deactivations\Api\DeactivationController@destroy');
});

Route::resource('levels', 'Levels\Api\LevelsController');
Route::resource('lessons', 'Lessons\Api\LessonsController');
Route::resource('objectives', 'Objectives\Api\ObjectivesController');
Route::resource('statuses', 'Statuses\Api\StatusController');
Route::resource('recommendations', 'Recommendations\Api\RecommendationController');
Route::resource('logbook-categories', 'LogbookCategories\Api\LogbookCategoryController');
Route::resource('issues', 'Issues\Api\IssuesController');

Route::prefix('lessons-wip')->group(function () {
    Route::get('/', function () { return LessonWIP::all()->count(); });
    Route::post('/', 'LessonVersions\Api\LessonsWIPController@store');
    Route::put('{lesson}', 'LessonVersions\Api\LessonsWIPController@update');
    Route::delete('{lesson}', 'LessonVersions\Api\LessonsWIPController@destroy');
});

Route::resource('lesson-versions', 'LessonVersions\Api\LessonVersionsController');
Route::prefix('lesson-versions')->group(function () {
    Route::post('/', 'LessonVersions\Api\LessonVersionsController@store');
    Route::get('/create-version', 'LessonVersions\Api\LessonsWIPController@index');
});

Route::prefix('objectives-wip')->group(function () {
    Route::put('{objective}', 'LessonVersions\Api\ObjectivesWIPController@update');
    Route::delete('{objective}', 'LessonVersions\Api\ObjectivesWIPController@destroy');
    Route::post('/', 'LessonVersions\Api\ObjectivesWIPController@store');
});
