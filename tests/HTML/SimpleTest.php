<?php

require_once("tests/bootstrap.php");

FUnit::test("Simple::__construct()", function(){
	$var = new \Chevron\HTML\Simple(["div" => [
		"innerHTML" => "This is text",
		"data-id"   => "78",
		"class"     => ["active", "hot"],
	]);
	$expected = "<div data-id=\"78\" class=\"active hot\">This is text</div>";

	FUnit::equal($expected, "{$var}");
});