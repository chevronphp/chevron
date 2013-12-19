<?php

class BasicTest extends PHPUnit_Framework_TestCase {

	public function testSanitizeScalar(){
		$data     = "4e864\n4e993\x08e422743\x0739ebf\x0D7504a\x1A893b0";
		$expected = "4e864\n4e993 e422743 39ebf 7504a 893b0";

		// scalar
		$filtered = \Chevron\Filter\Basic::sanitize_scalar($data);
		$this->assertEquals($filtered, $expected, "Basic::sanitize (scalar) failed to sanitize");
	}

	public function testSanitizeArray(){
		$data = array(
			0        => "4e864\n4e99\x083e422743\x0739ebf\x0D7504a\x1A893b0",
			"string" => "4e864\n4e993\x08e422743\x0739ebf\x0D7504a\x1A893b0",
		);
		$expected = array(
			0        => "4e864\n4e99 3e422743 39ebf 7504a 893b0",
			"string" => "4e864\n4e993 e422743 39ebf 7504a 893b0",
		);

		// array
		$filtered = \Chevron\Filter\Basic::sanitize_array($data);
		$this->assertEquals($filtered, $expected, "Basic::sanitize (array) failed to sanitize");
	}

	public function testSanitizeRecursiveArray(){
		$data = array(
			0        => "4e864\n4e99\x083e422743\x0739ebf\x0D7504a\x1A893b0",
			"string" => "4e864\n4e993\x08e422743\x0739ebf\x0D7504a\x1A893b0",
			"array" => array(
				"string" => "4e864\n4e993e42\x08274339\x07ebf\x0D7504a\x1A893b0",
				"array" => array(
					"string" => "4e864\n4\x08e993e42274339ebf\x07\x0D7504a\x1A893b0",
				),
			),
		);

		$expected = array(
			0        => "4e864\n4e99 3e422743 39ebf 7504a 893b0",
			"string" => "4e864\n4e993 e422743 39ebf 7504a 893b0",
			"array" => array(
				"string" => "4e864\n4e993e42 274339 ebf 7504a 893b0",
				"array" => array(
					"string" => "4e864\n4 e993e42274339ebf  7504a 893b0",
				),
			),
		);

		// recursive array
		$filtered = \Chevron\Filter\Basic::sanitize_array($data);
		$this->assertEquals($filtered, $expected, "Basic::sanitize (recursive) failed to sanitize");
	}

}