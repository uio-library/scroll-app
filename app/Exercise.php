<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $casts = [
        'content' => 'object',
        'answer' => 'object',
    ];

    public $incrementing = false;
    protected $fillable = ['id', 'course_id', 'name', 'type', 'content', 'answer'];

    protected $helperMap = [
        "TextFieldQuestionRegex" => TextFieldQuestionRegex::class,
        "MultipleChoiceQuestion" => MultipleChoiceQuestion::class,
        "SelfAssessedExerciseWithSolution" => SelfAssessedExerciseWithSolution::class,
        "DummyExercise" => DummyExercise::class,
    ];

    protected $helperClass = null;


    public function checkAnswer($answer)
    {
        $helperClass = new $this->helperMap[$this->type]($this);
        return $helperClass->checkAnswer($answer);
    }

    /**
     * Get the coure that owns the exercise.
     */
    public function course()
    {
        return $this->belongsTo('App\Course');
    }
}
