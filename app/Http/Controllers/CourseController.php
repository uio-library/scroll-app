<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Course;

class CourseController extends Controller
{
    public function index(Request $request) {
        return view('courses.index', ['courses' => Course::get()]);
    }

    public function show(Request $request, $name) {
		$course = Course::where(['name' => $name])->firstOrFail();

		return view('courses.show', ['course' => $course]);
	}

    public function settings(Request $request, $name) {
        $course = Course::where(['name' => $name])->firstOrFail();

        return view('courses.settings', ['course' => $course]);
    }

    public function saveSettings(Request $request) {

    }

    public function new(Request $request) {
        return view('courses.new', []);
    }

    public function saveNew(Request $request) {

    }
}
