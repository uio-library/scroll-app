<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Course;

class CourseController extends Controller
{
    public function getCourse(Request $request, $name) {
    	$course = Course::where(['name' => $name])->firstOrFail();
    	
    	return view('course', ['course' => $course]);
    }

    public function listCourses(Request $request) {
    	return view('course');
    }
}
