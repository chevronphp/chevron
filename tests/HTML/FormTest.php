<?php

require_once("tests/bootstrap.php");

use \Chevron\HTML;

FUnit::test("HTML\Form::__callStatic() w/o attributes", function(){
	$dispatcher = new HTML\FormDispatcher;

	$el = $dispatcher->text("name", "value");
	Funit::equal("<input name=\"name\" type=\"text\" value=\"value\" />", $el->render());
});

FUnit::test("HTML\Form::__callStatic() w/ attributes", function(){
	$dispatcher = new HTML\FormDispatcher;

	$el = $dispatcher->text("name", "value", null, [
		"class" => array("testClass", "testClass2"),
		"id"    => "testID",
	]);
	Funit::equal("<input class=\"testClass testClass2\" id=\"testID\" name=\"name\" type=\"text\" value=\"value\" />", $el->render());
});

FUnit::test("HTML\Form::setChecked()", function(){
	$dispatcher = new HTML\FormDispatcher;

	$el = $dispatcher->radio("name", "value", true);
	Funit::equal("<input checked=\"checked\" name=\"name\" type=\"radio\" value=\"value\" />", $el->render());
});

