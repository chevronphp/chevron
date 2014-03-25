<?php

FUnit::test("HTML::__callStatic() w/o args", function(){

	$el = \Chevron\HTML\HTML::p();

	if(!($el InstanceOf \Chevron\HTML\HTML)){
		FUnit::fail("incorrect InstanceOf");
	}

	$reflection = new ReflectionClass($el);

	$tag = $reflection->getProperty("tag");
	$tag->setAccessible(true);
	$value = $tag->getValue($el);

	FUnit::equal($value, "p", "tag");

});

FUnit::test("HTML::__callStatic() w/ args", function(){

	$el = \Chevron\HTML\HTML::p("this is some text", array("key" => "value", "disabled"));

	$reflection = new ReflectionClass($el);

	$innerHTML = $reflection->getProperty("innerHTML");
	$innerHTML->setAccessible(true);
	$value = $innerHTML->getValue($el);
	$expected = "this is some text";

	FUnit::equal($value, $expected, "innerHTML");

	$attributes = $reflection->getProperty("attributes");
	$attributes->setAccessible(true);
	$value = $attributes->getValue($el);
	$expected = array("key" => "value", "disabled");

	FUnit::equal($value, $expected, "attrs");

});



