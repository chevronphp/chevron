<?php

class FILOTest extends PHPUnit_Framework_TestCase {

	public function test_FILO_push() {

		$filo = new \Chevron\Stacks\FILO;

		$filo->push("one");
		$filo->push("two");
		$filo->push("three");

		$expected = 3;
		$value = $filo->length();

		$this->assertEquals($expected, $value, "Stacks::FILO failed to push");

	}

	public function test_FILO_pop() {

		$filo = new \Chevron\Stacks\FILO;

		$filo->push("one");
		$filo->push("two");
		$filo->push("three");

		$expected = "three";
		$value = $filo->pop();

		$this->assertEquals($expected, $value, "Stacks::FILO failed to pop");

	}

}