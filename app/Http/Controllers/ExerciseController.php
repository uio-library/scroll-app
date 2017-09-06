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
        $isCorrect = $exercise->checkAnswer($answer);
        $request->session()->put("exercise:$exercise->id", ['answer' => $answer, 'isCorrect' => $isCorrect]);
    	return response()->json(['correct' => $isCorrect]);
    }

    public function getExercise(Request $request)
    {
        if (!isset($request->name)) {
            return response()->json(['error' => 'Missing query string parameter: name'], 400);
        }

    	$exercise = Exercise::where('name', '=', $request->name)->first();

    	if (is_null($exercise)) {
    		return response()->json(['error' => 'Exercise not found'], 400);
    	}

    	return response()->json([
            'content' => $exercise->content, 
            'id' => $exercise->id,
            'state' => $request->session()->get("exercise:$exercise->id", ['answer'=>'', 'isCorrect'=>null]), 
        ]);
    }
}
