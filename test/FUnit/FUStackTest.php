<?php
use \FUnit\fu;

require __DIR__ . '/../../src/Chevron/_init/bootstrap.php';
require __DIR__ . '/../../../FUnit/FUnit.php';

fu::test("push", function(){

	$stack = new \Chevron\Stack\Stack;
	$expected1 = array("one", "two");
	$expected2 = array("three", "four");

	$stack->push($expected1);
	$stack->push($expected2);

	$expected = array(
		$expected1,
		$expected2
	);

	fu::strict_equal($expected, $stack->stack, "PUSH failed to push");

});

fu::test("pop", function(){

	$stack = new \Chevron\Stack\Stack;
	$expected1 = array("one", "two");
	$expected2 = array("three", "four");

	$stack->stack[] = $expected1;
	$stack->stack[] = $expected2;

	$value = $stack->pop();

	fu::strict_equal($expected2, $value, "POP failed to pop");

});

fu::test("unshift", function(){

	$stack = new \Chevron\Stack\Stack;
	$expected1 = array("one", "two");
	$expected2 = array("three", "four");

	$stack->unshift($expected1);
	$stack->unshift($expected2);

	$expected = array(
		$expected2,
		$expected1
	);

	fu::strict_equal($expected, $stack->stack, "UNSHIFT failed to unshift");

});

fu::test("shift", function(){

	$stack = new \Chevron\Stack\Stack;
	$expected1 = array("one", "two");
	$expected2 = array("three", "four");

	$stack->stack[] = $expected1;
	$stack->stack[] = $expected2;

	$value = $stack->shift();

	fu::strict_equal($expected1, $value, "SHIFT failed to shift");

});

fu::test("length", function(){

	$stack = new \Chevron\Stack\Stack;
	$expected1 = array("one", "two");
	$expected2 = array("three", "four");

	$stack->stack[] = $expected1;
	$stack->stack[] = $expected2;

	$value = $stack->length();

	fu::strict_equal(2, $value, "LENGTH failed to return the correct length");

});

$code = fu::run();
exit($code);