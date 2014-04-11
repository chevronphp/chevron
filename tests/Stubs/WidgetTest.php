<?php

require_once("tests/bootstrap.php");

use \Chevron\Stubs\Widget;

FUnit::test("Widget::__construct()", function(){
	$widget = new Widget(__DIR__."/exampleView.php");
	FUnit::ok(($widget InstanceOf Widget));
});

FUnit::test("Widget::__construct()/__get() w/ data", function(){
	$widget = new Widget(__DIR__."/exampleView.php", ["test" => "data"]);
	FUnit::equal("data", $widget->test);
});

FUnit::test("Widget::__isset()", function(){
	$widget = new Widget(__DIR__."/exampleView.php", ["test" => "data"]);
	FUnit::ok(isset($widget->test));
	FUnit::not_ok(isset($widget->notThere));
});

FUnit::test("Widget::setMany()", function(){
	$widget = new Widget(__DIR__."/exampleView.php");
	$widget->setMany(["test" => "data"]);
	FUnit::ok(isset($widget->test));
	FUnit::not_ok(isset($widget->notThere));
});

FUnit::test("Widget::render()/__invoke()", function(){
	$widget = new Widget(__DIR__."/exampleView.php");
	FUnit::ok($widget());
});

FUnit::test("Widget::__toString()", function(){
	$widget = new Widget(__DIR__."/exampleView.php");
	FUnit::equal(__DIR__."/exampleView.php", (string)$widget);
});



