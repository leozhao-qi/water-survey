<?php

use Illuminate\Support\Facades\DB;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get(
    '/users', 'Users\UsersController@index'
);

Route::get(
    '/users/{user}', 'Users\UsersController@show'
);

Route::get(
    '/api/users/deactivations', 'Users\Api\UsersController@inactiveIndex'
);

Route::get(
    '/api/users/{user}', 'Users\Api\UsersController@show'
);

Route::get(
    '/api/users/moodle/create', 'MoodleUsers\Api\MoodleUsersController@create'
);

Route::post(
    '/api/users/{user}/role/{role}', 'Supervisors\Api\SupervisorsController@store'
);

Route::post(
    '/api/users/moodle', 'MoodleUsers\Api\MoodleUsersController@store'
);

Route::put(
    '/api/users/{user}/password', 'Users\Api\PasswordChangeController@update'
);

Route::put(
    '/api/users/{user}/appointment', 'Users\Api\AppointmentDateController@update'
);

Route::put(
    '/api/users/{user}/role', 'Users\Api\RoleChangeController@update'
);

Route::get(
    '/api/users', 'Users\Api\UsersController@index'
);

Route::get(
    '/api/roles', 'Roles\Api\RolesController@index'
);

Route::put(
    '/api/deactivations/{deactivation}', 'Deactivations\Api\DeactivationController@update'
);

Route::post(
    '/api/deactivations/{user}', 'Deactivations\Api\DeactivationController@store'
);

Route::put(
    '/api/deactivations/{user}/activate', 'Deactivations\Api\DeactivationController@activate'
);

Route::delete(
    '/api/deactivations/{deactivation}', 'Deactivations\Api\DeactivationController@destroy'
);

Route::get(
    '/api/supervisors/{role}', 'Supervisors\Api\SupervisorsController@index'
);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
