<?php

class ElementTest extends PHPUnit_Framework_TestCase {

	public function test_setAttributes(){
		$el = new \Chevron\HTML\Element("div");

		$el->setAttributes(array(
			"class" => array("testClass", "testClass2"),
			"id"    => "testID",
		));

		$this->assertEquals(array("testClass", "testClass2"), $el->class, "Element::setAttributes() failed to set the class");
		$this->assertEquals("testID", $el->id, "Element::setAttributes() failed to set the class");
	}

	public function test_setInnerHTML(){
		$el = new \Chevron\HTML\Element("div");

		$el->setInnerHTML("This is a <bad>data string</bad>");

		$this->assertEquals("This is a &lt;bad&gt;data string&lt;/bad&gt;", $el->innerHTML, "Element::setInnerHTML() failed to set the innerHTML");
	}

	public function test_setSelfClosing(){
		$el = new \Chevron\HTML\Element("div");

		$el->setSelfClosing(true);

		$this->assertEquals(true, $el->isSelfClosing, "Element::setSelfClosing() failed to set the isSelfClosing");
	}

	public function test_setEmpty(){
		$el = new \Chevron\HTML\Element("div");

		$el->setEmpty(true);

		$this->assertEquals(true, $el->isEmpty, "Element::setEmpty() failed to set the isEmpty");
	}

	public function test__toString(){
		$el = new \Chevron\HTML\Element("div");

		$el->setAttributes(array(
			"class" => array("testClass", "testClass2"),
			"id"    => "testID",
		));

		$this->assertEquals("<div class=\"testClass testClass2\" id=\"testID\"></div>", (string)$el, "Element::__toString() failed to stringify");
	}

}