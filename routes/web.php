<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/

Route::domain('scroll-app.uio.no')->group(function () {
    Route::get('/', 'CourseController@index');
});

Route::domain('{name}.uio.no')->group(function () {
    Route::get('/', 'CourseController@getCourse');
});

Route::get('/', 'CourseController@index');

/*
|--------------------------------------------------------------------------
| Course consumption
|--------------------------------------------------------------------------
*/

Route::post('resetCourse', 'CourseController@resetCourse')->name('courses.reset');
Route::get('getQuiz', 'ExerciseController@getQuiz');
Route::post('checkAnswers', 'ExerciseController@checkAnswers');

/*
|--------------------------------------------------------------------------
| Course management
|--------------------------------------------------------------------------
*/

// Route::get('courses', 'CourseController@index')->name('courses.index');
Route::get('courses/{name}', 'CourseController@show')->name('courses.show');
Route::group(['middleware' => ['can:update,course']], function () {
    $this->get('courses/{name}/settings', 'CourseController@settings')->name('courses.settings');
    $this->post('courses/{name}/settings', 'CourseController@saveSettings')->name('courses.settings.save');
});
Route::group(['middleware' => ['can:create,course']], function () {
    $this->get('courses/{name}/new', 'CourseController@new')->name('courses.new');
    $this->post('courses/{name}/new', 'CourseController@saveNew')->name('courses.new.save');
});

/*
|--------------------------------------------------------------------------
| User management
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['can:show,App\User']], function () {
    $this->get('users', 'UsersController@index')->name('users.index');
    $this->get('users/{user}', 'UsersController@show')->name('users.show');
});


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['guest']], function () {

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
});

// Account routes
Route::group(['middleware' => ['auth']], function () {
    $this->post('logout', 'Auth\LoginController@samlLogout')->name('logout');
    $this->get('account', 'Auth\LoginController@account')->name('account');
});

Route::get('saml2/error', 'Auth\LoginController@error');

