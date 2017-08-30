<?php

namespace App;

class TextFieldQuestionRegex 
{
	function __construct($exercise)
	{
		$this->exercise = $exercise;
	}

    function checkAnswer($answer) 
    {
    	if (preg_match($this->exercise->answer->regex, $answer))
    	{
    		return true;
    	}

    	else {
    		return false;
    	}
    }
}
