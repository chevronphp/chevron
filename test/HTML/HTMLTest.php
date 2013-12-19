<?php

class HTMLTest extends PHPUnit_Framework_TestCase {

	public function getDependencyMessage($err){
		$msg = "{$err}\n\n###\nThe HTML class is a very thin wrapper/shortcut for the Element class and since this one failed"
				." it might also be worth your time checking the tests for the Element class.";
		return $msg;
	}

	public function test_instance(){

		$el = \Chevron\HTML\HTML::p();

		$this->assertInstanceOf("\Chevron\HTML\HTML", $el, "HTML::__callStatic failed to return the correct instance type");

	}

	public function test_tag(){

		$el = \Chevron\HTML\HTML::p();

		$reflection = new ReflectionClass($el);

		$tag = $reflection->getProperty("tag");
		$tag->setAccessible(true);
		$value = $tag->getValue($el);

		$this->assertEquals($value, "p", $this->getDependencyMessage("HTML::__callStatic failed to set the correct tag"));

	}

	public function test_innerHTML(){

		$el = \Chevron\HTML\HTML::p("this is some text");

		$reflection = new ReflectionClass($el);

		$innerHTML = $reflection->getProperty("innerHTML");
		$innerHTML->setAccessible(true);
		$value = $innerHTML->getValue($el);
		$expected = "this is some text";

		$this->assertEquals($value, $expected, "HTML::__callStatic failed to set the correct innerHTML");

	}

	public function test_attrs(){

		$el = \Chevron\HTML\HTML::p("this is some text", array("key" => "value", "disabled"));

		$reflection = new ReflectionClass($el);

		$attributes = $reflection->getProperty("attributes");
		$attributes->setAccessible(true);
		$value = $attributes->getValue($el);
		$expected = array("key" => "value", "disabled");

		$this->assertEquals($value, $expected, "HTML::__callStatic failed to set the correct attributes");

	}

}

