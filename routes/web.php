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

Route::get('/', function () {
    return view('welcome');
});

Route::post('checkAnswer', 'ExerciseController@checkAnswer');

Route::post('resetCourse', 'CourseController@resetCourse');

Route::get('getExercise', 'ExerciseController@getExercise');

Route::get('course', 'CourseController@listCourses');

Route::get('course/{id}', 'CourseController@getCourse');