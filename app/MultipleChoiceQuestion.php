<?php

namespace App;

class MultipleChoiceQuestion
{
    public function __construct($exercise)
    {
        $this->exercise = $exercise;
    }

    public function checkAnswer($answer)
    {
        if ($this->exercise->answer->text == $answer) {
            return true;
        } else {
            return false;
        }
    }
}
