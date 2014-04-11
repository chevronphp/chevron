<?php

require_once("tests/bootstrap.php");

use \Chevron\Stubs\Virtual;

FUnit::test("Virtual::render()", function(){
	$widget = new Virtual(function(){
		return 999;
	});

	$expected = 999;
	$value = $widget->render();

	FUnit::equal($expected, $value);
});

FUnit::test("Widget::__toString()", function(){
	$widget = new Virtual(function( $obj ){
		print($obj->placebo);
	}, ["placebo" => "effect"]);

	$expected = "effect";
	$value = (string)$widget;

	FUnit::equal($expected, $value);
});

