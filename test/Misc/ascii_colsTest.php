<?php

class Ascii_Cols_Test extends PHPUnit_Framework_TestCase {

	public function test_ascii_cols(){

		\Chevron\Misc\Loader::loadFunctions("ascii_cols");

		$rows = array();
		$rows[] = ["left one two", "three four right"];
		$rows[] = ["left five six", "seven ain't nine right"];

		$expected = "";
		$expected .= "left one two ...................................................................... three four right\n";
		$expected .= "left five six ............................................................... seven ain't nine right\n";

		$value = ascii_cols(100, $rows);
		$this->assertEquals($expected, $value, "ascii_cols failed to properly format the return string");

	}

}