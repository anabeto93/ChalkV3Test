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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('admins/register', 'Auth\RegisterController@createAdmin');
Route::post('admins/institutions.add', 'AdminController@institution');
Route::resources([
    'admins'    => 'AdminController',
    'cohorts'   => 'CohortController',
    'institutions'  =>  'InstitutionController',
    'courses'   =>  'CourseController'
]);

Route::get('graphql/test/tests', function (){
    return response()->json(['message' => 'welcome']);
})->middleware('auth.student');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
