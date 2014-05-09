<?php

use \Chevron\Stubs;

FUnit::test("Widget::__construct()", function(){

	$dispatcher = new Stubs\WidgetDispatcher(__DIR__);

	$widget = $dispatcher->get("/exampleView.php");
	FUnit::ok(($widget InstanceOf Widget));

	$widget = $dispatcher->get("exampleView.php");
	FUnit::ok(($widget InstanceOf Widget));
});