<?php

class ScanArgsTest extends PHPUnit_Framework_TestCase {

	function test_scan_args_as_expected(){

		Chevron\Misc\Loader::loadFunctions("scan_args");

		$base = array( "filename.php", "-val1", "value", "-flag" );

		$final = scan_args($base, array("val1"), array("flag"));

		$expected = array("val1" => "value", "flag" => true);

		$this->assertEquals($expected, $final, "scan_args (expected) failed to return expected results");

	}

	function test_scan_args_no_flag(){

		Chevron\Misc\Loader::loadFunctions("scan_args");

		$base = array( "filename.php", "-val1", "value" );

		$final = scan_args($base, array("val1"), array("flag"));

		$expected = array("val1" => "value", "flag" => false);

		$this->assertEquals($expected, $final, "scan_args (missing flag) failed to return expected results");

	}

	function test_scan_args_no_value(){

		Chevron\Misc\Loader::loadFunctions("scan_args");

		$base = array( "filename.php", "-flag" );

		$final = scan_args($base, array("val1"), array("flag"));

		$expected = array("flag" => true);

		$this->assertEquals($expected, $final, "scan_args (missing value) failed to return expected results");

	}

	function test_scan_args_unexpected_flag(){

		Chevron\Misc\Loader::loadFunctions("scan_args");

		$base = array( "filename.php", "-val1", "value", "-flag2" );

		$final = scan_args($base, array("val1"), array("flag"));

		$expected = array("val1" => "value", "flag" => false);

		$this->assertEquals($expected, $final, "scan_args (unexpected flag) failed to return expected results");

	}

	function test_scan_args_unexpected_value(){

		Chevron\Misc\Loader::loadFunctions("scan_args");

		$base = array( "filename.php", "-val2", "value", "-flag" );

		$final = scan_args($base, array("val1"), array("flag"));

		$expected = array("flag" => true);

		$this->assertEquals($expected, $final, "scan_args (unexpected value) failed to return expected results");

	}

	function test_scan_args_unexpected_both(){

		Chevron\Misc\Loader::loadFunctions("scan_args");

		$base = array( "filename.php", "-val2", "value", "-flag2" );

		$final = scan_args($base, array("val1"), array("flag"));

		$expected = array("flag" => false);

		$this->assertEquals($expected, $final, "scan_args (unexpected flag and value) failed to return expected results");

	}

}