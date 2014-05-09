<?php

use \Chevron\Stubs;

FUnit::test("Widget::__construct()", function(){

	$dispatcher = new Stubs\WidgetDispatcher(__DIR__);

	$widget = $dispatcher->make("/exampleView.php");
	FUnit::ok(($widget InstanceOf Widget));

	$widget = $dispatcher->make("exampleView.php");
	FUnit::ok(($widget InstanceOf Widget));
});