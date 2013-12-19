<?php

class FIFOTest extends PHPUnit_Framework_TestCase {

	public function test_FIFO_push() {

		$filo = new \Chevron\Stacks\FIFO;

		$filo->push("one");
		$filo->push("two");
		$filo->push("three");

		$expected = 3;
		$value = $filo->length();

		$this->assertEquals($expected, $value, "Stacks::FIFO failed to push");

	}

	public function test_FIFO_pop() {

		$filo = new \Chevron\Stacks\FIFO;

		$filo->push("one");
		$filo->push("two");
		$filo->push("three");

		$expected = "one";
		$value = $filo->pop();

		$this->assertEquals($expected, $value, "Stacks::FIFO failed to pop");

	}

}