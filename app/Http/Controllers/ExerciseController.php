<?php

namespace App\Http\Controllers;

use App\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Exercise;

class ExerciseController extends Controller
{
    /**
     * Return a list of exercises
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getQuiz(Request $request)
    {
        if (!isset($request->course)) {
            return response()->json(['error' => 'Missing query string parameter: course'], 400);
        }

        if (!isset($request->exercises)) {
            return response()->json(['error' => 'Missing query string parameter: exercises'], 400);
        }

        $course = Course::find($request->course);
        if (is_null($course)) {
            abort(400, 'Invalid course');
        }

        $exercises = [];
        foreach ($course->exercises()->whereIn('name', $request->exercises)->get() as $ex) {
            $exercises[$ex->name] = [
                'type' => $ex->type,
                'id' => $ex->id,
                'name' => $ex->name,
                'content' => $ex->content,
                'state' => $request->session()->get("exercise:$ex->id", [
                    'answer' => '',
                    'isCorrect' => null,
                ]),
            ];
        }

        return response()->json($exercises);
    }

    /**
     * Check answers for a list of questions
     *
     * @param Request $request
     * @return JsonResponse
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
