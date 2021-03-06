<?php

/*
|--------------------------------------------------------------------------
| User management
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['session', 'can:show,App\User']], function () {
    $this->get('users', 'UsersController@index')->name('users.index');
    $this->get('users/{user}', 'UsersController@show')->name('users.show');
});

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['session', 'guest']], function () {

    // Authentication
    $this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
    $this->post('login', 'Auth\LoginController@login');

    // Registration
    $this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    $this->post('register', 'Auth\RegisterController@register');

    // Password Reset
    $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    $this->post('password/reset', 'Auth\ResetPasswordController@reset');

    $this->get('saml2/error', 'Auth\LoginController@error');
});

Route::group(['middleware' => ['session', 'auth']], function () {
    $this->post('logout', 'Auth\LoginController@samlLogout')->name('logout');
    $this->get('account', 'Auth\LoginController@account')->name('account');

    $this->get('github/repos', 'GithubController@repos')->name('github.repos');
    $this->get('github/disconnect', 'GithubController@disconnect')->name('github.disconnect');
});

Route::group(['middleware' => ['session']], function () {
    $this->get('github/connect', 'GithubController@connect')->name('github.connect');
    $this->get('github/callback', 'GithubController@handleCallback');
});

/*
|--------------------------------------------------------------------------
| Course consumption
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['session']], function () {
    Route::domain('scroll-app.uio.no')->group(function () {
        Route::get('/', 'CourseController@index');
    });
});

Route::domain('{course}.uio.no')->group(function () {
    Route::get('/', 'CourseController@show');
    Route::get('/resources/{resource}', 'CourseController@resource')->name('courses.resource');
});


Route::group(['middleware' => ['session']], function () {
    Route::get('/', 'CourseController@index');

    Route::post('resetCourse', 'CourseController@resetCourse')->name('courses.reset');
    Route::get('getQuiz', 'ExerciseController@getQuiz');
    Route::post('checkAnswers', 'ExerciseController@checkAnswers');
});

Route::group(['middleware' => ['trailing_slash']], function () {
    Route::get('{course}', 'CourseController@show')->name('courses.show');
});
Route::get('{course}/resources/{resource}', 'CourseController@resource')->name('courses.resource');


/*
|--------------------------------------------------------------------------
| Course management
|--------------------------------------------------------------------------
*/

// Route::get('courses', 'CourseController@index')->name('courses.index');
Route::group(['middleware' => ['session', 'can:create,App\Course']], function () {
    $this->get('courses/new', 'CourseController@new')->name('courses.new');
    $this->post('courses/new', 'CourseController@saveNew')->name('courses.new.save');
});

Route::group(['middleware' => ['session', 'can:update,course']], function () {
    // $this->get('courses/{course}/settings', 'CourseController@settings')->name('courses.settings');
    // $this->post('courses/{course}/settings', 'CourseController@saveSettings')->name('courses.settings.save');
    $this->get('{course}/create-github-hook', 'CourseController@createGithubHook')->name('courses.ghhook');
    $this->get('{course}/test-github-hook', 'CourseController@testGithubHook')->name('courses.ghtest');
});
