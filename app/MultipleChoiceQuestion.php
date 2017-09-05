<?php

namespace App;

class MultipleChoiceQuestion
{
    function __construct($exercise)
	{
		$this->exercise = $exercise;
	}

    function checkAnswer($answer) 
    {
    	if ($this->exercise->answer->text == $answer)
    	{
    		return true;
    	}

    	else {
    		return false;
    	}
    }
}
