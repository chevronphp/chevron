<?php

require_once("tests/bootstrap.php");

use \Chevron\HTML;

FUnit::test("HTML\Element::__callStatic() w/o attributes", function(){
	$el = HTML\Element::div("This is a <bad>data string</bad>");
	Funit::equal("<div>This is a &lt;bad&gt;data string&lt;/bad&gt;</div>", $el->render());
});

FUnit::test("HTML\Element::__callStatic() w/ attributes", function(){
	$el = HTML\Element::div("boom", [
		"class" => array("testClass", "testClass2"),
		"id"    => "testID",
	]);
	Funit::equal("<div class=\"testClass testClass2\" id=\"testID\">boom</div>", $el->render());
});

