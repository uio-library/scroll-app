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

Route::domain('{name}.uio.no')->group(function () {
    Route::get('/', 'CourseController@getCourse');
});

Route::get('/', function () {
    return view('welcome');
});

Route::post('resetCourse', 'CourseController@resetCourse');

Route::get('course', 'CourseController@listCourses');

Route::get('course/{name}', 'CourseController@getCourse');

Route::get('getQuiz', 'ExerciseController@getQuiz');

Route::post('checkAnswers', 'ExerciseController@checkAnswers');

