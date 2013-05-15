<?php

class StackTest extends PHPUnit_Framework_TestCase {

	public function test_push(){

		$stack = new \Chevron\Stack\Stack;
		$expected1 = array("one", "two");
		$expected2 = array("three", "four");

		$stack->push($expected1);
		$stack->push($expected2);

		$expected = array(
			$expected1,
			$expected2
		);

		$this->assertEquals($expected, $stack->stack, "PUSH failed to push");

	}

	public function test_pop(){

		$stack = new \Chevron\Stack\Stack;
		$expected1 = array("one", "two");
		$expected2 = array("three", "four");

		$stack->stack[] = $expected1;
		$stack->stack[] = $expected2;

		$value = $stack->pop();

		$this->assertEquals($expected2, $value, "POP failed to pop");

	}

	public function test_unshift(){

		$stack = new \Chevron\Stack\Stack;
		$expected1 = array("one", "two");
		$expected2 = array("three", "four");

		$stack->unshift($expected1);
		$stack->unshift($expected2);

		$expected = array(
			$expected2,
			$expected1
		);

		$this->assertEquals($expected, $stack->stack, "UNSHIFT failed to unshift");

	}

	public function test_shift(){

		$stack = new \Chevron\Stack\Stack;
		$expected1 = array("one", "two");
		$expected2 = array("three", "four");

		$stack->stack[] = $expected1;
		$stack->stack[] = $expected2;

		$value = $stack->shift();

		$this->assertEquals($expected1, $value, "SHIFT failed to shift");

	}

	public function test_length(){

		$stack = new \Chevron\Stack\Stack;
		$expected1 = array("one", "two");
		$expected2 = array("three", "four");

		$stack->stack[] = $expected1;
		$stack->stack[] = $expected2;

		$value = $stack->length();

		$this->assertEquals(2, $value, "LENGTH failed to return the correct length");

	}

}