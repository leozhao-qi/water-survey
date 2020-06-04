<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::put(
    '/api/files/{file}',
    'Logbooks\Api\LogbookFilesController@updateFilename'
);

Route::delete(
    '/api/files/{file}',
    'Logbooks\Api\LogbookFilesController@destroy'
);

Route::middleware(['download'])->group(function () {
	Route::get(
		'/storage/entries',
		'Logbooks\Api\LogbookFilesController@download'
    );

	// Route::delete(
	// 	'/storage/entries/{user}/{logbookEntry}/{file}',
	// 	'\TrainingTracker\Http\LogbookEntries\Controllers\Api\LogbookEntryFilesController@destroy'
	// );

	// Route::patch(
	// 	'/storage/entries/{user}/{logbookEntry}/updatefiles',
	// 	'\TrainingTracker\Http\LogbookEntries\Controllers\Api\LogbookEntryFilesController@update'
	// );
});

Route::resource('users', 'Users\UsersController');

Route::prefix('api/users')->group(function () {
    Route::get(
        'deactivations', 'Users\Api\UsersController@inactiveIndex'
    );

    Route::get('{user}', 'Users\Api\UsersController@show');

    Route::get('moodle/create', 'MoodleUsers\Api\MoodleUsersController@create');

    Route::post('{user}/role/{role}', 'Supervisors\Api\SupervisorsController@store');

    Route::post('{user}/packages', 'Packages\Api\PackageController@store');

    Route::post('moodle', 'MoodleUsers\Api\MoodleUsersController@store');

    Route::put('{user}/password', 'Users\Api\PasswordChangeController@update');

    Route::put('{user}/appointment', 'Users\Api\AppointmentDateController@update');

    Route::put('{user}/role', 'Users\Api\RoleChangeController@update');

    Route::get('/', 'Users\Api\UsersController@index');

    Route::delete('{user}', 'Users\Api\UsersController@destroy');
});

Route::get(
    '/api/roles', 'Roles\Api\RolesController@index'
);

Route::prefix('api/deactivations')->group(function () {
    Route::put('{deactivation}', 'Deactivations\Api\DeactivationController@update');

    Route::post('{user}', 'Deactivations\Api\DeactivationController@store');

    Route::put('{user}/activate', 'Deactivations\Api\DeactivationController@activate');

    Route::delete('{deactivation}', 'Deactivations\Api\DeactivationController@destroy');
});

Route::get('/api/supervisors/{role}', 'Supervisors\Api\SupervisorsController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/levels', 'Levels\LevelsController@index');

Route::resource('api/levels', 'Levels\Api\LevelsController');

Route::get('/lessons', 'Lessons\LessonsController@index');

Route::resource('api/lessons', 'Lessons\Api\LessonsController');

Route::get('/lesson-versions', 'LessonVersions\LessonVersionsController@index');

Route::get('/lesson-versions/create', 'LessonVersions\LessonVersionsController@create');

Route::post('/api/lesson-versions', 'LessonVersions\Api\LessonVersionsController@store');

Route::get('/api/lesson-versions/create-version', 'LessonVersions\Api\LessonsWIPController@index');

Route::put('/api/lessons-wip/{lesson}', 'LessonVersions\Api\LessonsWIPController@update');

Route::delete('/api/lessons-wip/{lesson}', 'LessonVersions\Api\LessonsWIPController@destroy');

Route::post('/api/lessons-wip', 'LessonVersions\Api\LessonsWIPController@store');

Route::resource('api/lesson-versions', 'LessonVersions\Api\LessonVersionsController');

Route::get('/objectives', 'Objectives\ObjectivesController@index');

Route::resource('api/objectives', 'Objectives\Api\ObjectivesController');

Route::put('/api/objectives-wip/{objective}', 'LessonVersions\Api\ObjectivesWIPController@update');

Route::delete('/api/objectives-wip/{objective}', 'LessonVersions\Api\ObjectivesWIPController@destroy');

Route::post('/api/objectives-wip', 'LessonVersions\Api\ObjectivesWIPController@store');

Route::get('/users/{user}/packages/{package}', 'Packages\PackageController@show');

Route::get('/api/users/{user}/packages/{package}', 'Packages\Api\PackageController@show');

Route::put('api/users/{user}/packages', 'Packages\Api\PackageController@add');

Route::put('api/users/{user}/packages/{package}', 'Packages\Api\PackageController@update');

Route::get('/statuses', 'Statuses\StatusController@index');

Route::resource('/api/statuses', 'Statuses\Api\StatusController');

Route::get('/recommendations', 'Recommendations\RecommendationController@index');

Route::resource('/api/recommendations', 'Recommendations\Api\RecommendationController');

Route::get('/logbook-categories', 'LogbookCategories\LogbookCategoryController@index');

Route::resource('/api/logbook-categories', 'LogbookCategories\Api\LogbookCategoryController');

Route::get('/logbooks', 'Logbooks\LogbookController@index');

Route::resource('/api/logbooks', 'Logbooks\Api\LogbookController');

Route::post('/api/logbooks/files/upload', 'Logbooks\Api\LogbookFilesController@upload');

Route::post('/api/logbooks/filemeta', 'Logbooks\Api\LogbookFilesController@meta');