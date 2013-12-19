<?php

class Between_Test extends PHPUnit_Framework_TestCase {

	public function test_betweenTrue(){

		\Chevron\Misc\Loader::loadFunctions("between");

		$val = 7;
		$min = 2;
		$max = 9;

		$result = between($val, $min, $max);

		$this->assertEquals(true, $result, "between (true)failed ... It's a very simply function, how'd you mess it up?");

	}

	public function test_betweenFalse(){

		\Chevron\Misc\Loader::loadFunctions("between");

		$val = 12;
		$min = 2;
		$max = 9;

		$result = between($val, $min, $max);

		$this->assertEquals(false, $result, "between (false) failed ... It's a very simply function, how'd you mess it up?");

	}

	public function test_betweenNumericString(){

		\Chevron\Misc\Loader::loadFunctions("between");

		$val = "12";
		$min = 2;
		$max = 9;

		$result = between($val, $min, $max);

		$this->assertEquals(false, $result, "between (numeric string) failed ... It's a very simply function, how'd you mess it up?");

	}

	public function test_betweenStringString(){

		\Chevron\Misc\Loader::loadFunctions("between");

		$val = "help";
		$min = 2;
		$max = 9;

		$result = between($val, $min, $max);

		$this->assertEquals(false, $result, "between (string string) failed ... It's a very simply function, how'd you mess it up?");

	}

}