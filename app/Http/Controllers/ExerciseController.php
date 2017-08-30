<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exercise;

class ExerciseController extends Controller
{
    public function checkAnswer(Request $request)
    {
    	$exercise = Exercise::find($request->id);
    	$answer = $request->answer;
    	return response()->json(['correct' => $exercise->checkAnswer($answer)]);
    }

    public function getExercise(Request $request)
    {

    	
    	$exercise = Exercise::where('name', '=', $request->name)->first();
    	
    	if (is_null($exercise)) {
    		return response()->json(['error' => 'name not defined'], 400);
    	}

    	return response()->json(['content' => $exercise->content, 'id' => $exercise->id]);
    }
}
