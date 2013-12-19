<?php

class Array_Flatten_Unique_Test extends PHPUnit_Framework_TestCase {

	public function test_array_flatten_unique(){

		\Chevron\Misc\Loader::loadFunctions("array_flatten_unique");

		$one = array("this key is empty", 500 => "this key is 500", 600 => array("step one", array("step 2.1", "step 2.2")));
		$two = array(500 => "this key is 502", "a string" => "this key is a string", "this key is another empty key");

		$expected = array(
			"step 2.1",
			"step 2.2",
			500 => "this key is 502",
			"a string" => "this key is a string",
			501 => "this key is another empty key",
		);

		$value = array_flatten_unique($one, $two);
		$this->assertEquals($expected, $value, "array_kmerge failed to merge while preserving keys");

	}

}