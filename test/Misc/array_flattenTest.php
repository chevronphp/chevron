<?php

class Array_Flatten_Test extends PHPUnit_Framework_TestCase {

	public function test_array_flatten(){

		\Chevron\Misc\Loader::loadFunctions("array_flatten");

		$one = array("this key is empty", 500 => "this key is 500", 600 => array("step one", array("step 2.1", "step 2.2")));
		$two = array(500 => "this key is 502", "a string" => "this key is a string", "this key is another empty key");
		$expected = array(
			"this key is empty",
			"this key is 500",
			"step one",
			"step 2.1",
			"step 2.2",
			"this key is 502",
			"this key is a string",
			"this key is another empty key",
		);

		$value = array_flatten($one, $two);
		$this->assertEquals($expected, $value, "array_kmerge failed to merge while preserving keys");

	}

}