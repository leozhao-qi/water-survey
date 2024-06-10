<?php

use App\LessonWIP;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/topics', 'Topics\TopicController@index');
Route::get('/issues', 'Issues\IssuesController@index');
Route::get('/levels', 'Levels\LevelsController@index');
Route::get('/lessons', 'Lessons\LessonsController@index');
Route::get('/lesson-versions', 'LessonVersions\LessonVersionsController@index');
Route::get('/lesson-versions/create', 'LessonVersions\LessonVersionsController@create');
Route::get('/objectives', 'Objectives\ObjectivesController@index');
Route::get('/statuses', 'Statuses\StatusController@index');
Route::get('/recommendations', 'Recommendations\RecommendationController@index');
Route::get('/logbook-categories', 'LogbookCategories\LogbookCategoryController@index');
Route::get('/logbooks', 'Logbooks\LogbookController@index');
Route::get('/reports', 'Reports\ReportController@index');
Route::get('/reports/users/download', 'Reports\UsersReportController@download');
Route::get('/reports/sot/{user}', 'Sot\SotController@show');
Route::get('/reports/sot/{user}/download', 'Sot\SotController@download');
Route::get('/reports/rot', 'Rot\RotController@index');
Route::get('/users/{user}/packages/{package}', 'Packages\PackageController@show');

Route::resource('users', 'Users\UsersController');

Route::middleware(['download'])->group(function () {
    Route::get(
        '/storage/entries',
        'Logbooks\Api\LogbookFilesController@download'
    );
});
