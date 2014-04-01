<?php

require_once("tests/bootstrap.php");

use \Chevron\HTML;

FUnit::test("Element::setAttributes()", function(){
	$el = new HTML\Element("div");
	$el->setAttributes(array(
		"class" => array("testClass", "testClass2"),
		"id"    => "testID",
	));
	Funit::equal(array("testClass", "testClass2"), $el->class, "array");
	Funit::equal("testID", $el->id, "scalar");
});

FUnit::test("Element::setInnerHTML()", function(){
	$el = new HTML\Element("div");
	$el->setInnerHTML("This is a <bad>data string</bad>");
	Funit::equal("This is a &lt;bad&gt;data string&lt;/bad&gt;", $el->innerHTML);
});

FUnit::test("Element::setSelfClosing()", function(){
	$el = new HTML\Element("div");
	$el->setSelfClosing(true);
	Funit::equal(true, $el->isSelfClosing);
});

FUnit::test("Element::setEmpty()", function(){
	$el = new HTML\Element("div");
	$el->setEmpty(true);
	Funit::equal(true, $el->isEmpty);
});

FUnit::test("Element::__toString()", function(){
	$el = new HTML\Element("div");
	$el->setAttributes(array(
		"class" => array("testClass", "testClass2"),
		"id"    => "testID",
	));
	Funit::equal("<div class=\"testClass testClass2\" id=\"testID\"></div>", (string)$el);
});

