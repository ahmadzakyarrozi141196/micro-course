<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//default menggunakan /api
//harus ditambahkan akhiran s karena ditable namanya ada s
Route::post('mentors', 'MentorController@create');
Route::put('mentors/{id}', 'MentorController@update');
Route::get('mentors', 'MentorController@index');
//ngambil kontroller dengan endpoint show
Route::get('mentors/{id}', 'MentorController@show');
Route::delete('mentors/{id}', 'MentorController@destroy');

//Router Course
Route::post('courses', 'CourseController@create');
Route::put('courses/{id}', 'CourseController@update');
Route::get('courses/{id}', 'CourseController@show');
Route::get('courses', 'CourseController@index');
Route::delete('courses/{id}', 'CourseController@destroy');

//Router Chapter
Route::post('chapters', 'ChapterController@create');
Route::put('chapters/{id}', 'ChapterController@update');
Route::get('chapters', 'ChapterController@index');
Route::get('chapters/{id}', 'ChapterController@show');
Route::delete('chapters/{id}', 'ChapterController@destroy');

//Router Lesson
Route::post('lessons', 'LessonController@create');
Route::put('lessons/{id}', 'LessonController@update');
Route::get('lessons', 'LessonController@index');
Route::get('lessons/{id}', 'LessonController@show');
Route::delete('lessons/{id}', 'LessonController@destroy');

//Router Image
Route::post('image-courses', 'ImageCourseController@create');
Route::delete('image-courses/{id}', 'ImageCourseController@destroy');

//Router Mycourse
Route::post('my_courses', 'MyCourseController@create');
Route::get('my_courses', 'MyCourseController@index');
Route::post('my_courses/premium', 'MyCourseController@createPremiumAccess');

//Router Review
Route::post('reviews', 'ReviewController@create');
Route::put('reviews/{id}', 'ReviewController@update');
Route::delete('reviews/{id}', 'ReviewController@destroy');
