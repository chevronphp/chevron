<?php

require_once("tests/bootstrap.php");

use \Chevron\Stubs\VirtualWidget;

FUnit::test("VirtualWidget::render()", function(){
	$widget = new VirtualWidget(function(){
		return 999;
	});

	$expected = 999;
	$value = $widget->render();

	FUnit::equal($expected, $value);
});

FUnit::test("Widget::__toString()", function(){
	$widget = new VirtualWidget(function( $obj ){
		print($obj->placebo);
	}, ["placebo" => "effect"]);

	$expected = "effect";
	$value = (string)$widget;

	FUnit::equal($expected, $value);
});

