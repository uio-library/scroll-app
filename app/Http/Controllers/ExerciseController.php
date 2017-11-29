<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exercise;

class ExerciseController extends Controller
{
    /**
     * Return a list of exercises
     */
    public function getQuiz(Request $request)
    {
        if (!isset($request->questions)) {
            return response()->json(['error' => 'Missing query string parameter: questions'], 400);
        }

        $exercises = [];
        foreach ($request->questions as $name) {
            $exercise = Exercise::where('name', '=', $name)->first();
            if (is_null($exercise)) {
                return response()->json(['error' => 'Exercise not found: ' . $name], 400);
            }
            $exercises[$name] = [
                'type' => $exercise->type,
                'id' => $exercise->id,
                'name' => $exercise->name,
                'content' => $exercise->content,
                'state' => $request->session()->get("exercise:$exercise->id", [
                    'answer' => '',
                    'isCorrect' => null,
                ]),
            ];
        }

        return response()->json($exercises);
    }

    /**
     * Check answers for a list of questions
     */
    public function checkAnswers(Request $request)
    {
        $isCorrect = [];
        foreach ($request->answers as $id => $answer) {
            $exercise = Exercise::find($id);
            $isCorrect[$exercise->id] = $exercise->checkAnswer($answer);
            $request->session()->put("exercise:$exercise->id", [
                'answer' => $answer,
                'isCorrect' => $isCorrect[$exercise->id],
            ]);

        }
        return response()->json($isCorrect);
    }
}
