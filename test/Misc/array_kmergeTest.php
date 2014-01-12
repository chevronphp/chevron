<?php

class Array_KMerge_Test extends PHPUnit_Framework_TestCase {

	public function test_array_kmerge(){

		\Chevron\Misc\Loader::loadFunctions("array_kmerge");

		$one = array("this key is empty", 500 => "this key is 500");
		$two = array(500 => "this key is 502", "a string" => "this key is a string", "this key is another empty key");
		$expected = array(
			0          => "this key is empty",
			500        => "this key is 502",
			"a string" => "this key is a string",
			501        => "this key is another empty key",
		);

		$value = array_kmerge($one, $two);
		$this->assertEquals($expected, $value, "array_kmerge failed to merge while preserving keys");

	}

}