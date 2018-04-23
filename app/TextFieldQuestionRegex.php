<?php

namespace App;

class TextFieldQuestionRegex
{
    public function __construct($exercise)
    {
        $this->exercise = $exercise;
    }

    public function checkAnswer($answer)
    {
        if (preg_match($this->exercise->answer->regex, $answer)) {
            return true;
        } else {
            return false;
        }
    }
}
