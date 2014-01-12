<?php

class ElementTest extends PHPUnit_Framework_TestCase {

	public function test_simple_constructor(){
		try {
			$el = new \Chevron\HTML\Element("p");
		} catch (\Exception $e) {
			$this->fail($e->getMessage());
		}

		$this->assertInstanceOf("\Chevron\HTML\Element", $el, "Element::__construct (simple) failed to return an instace of Chevron\HTML\Element");
	}

	public function test_complex_constructor_instance(){
		try {
			$el = new \Chevron\HTML\Element("p#id.class[key=value]");
		} catch (\Exception $e) {
			$this->fail($e->getMessage());
		}

		$this->assertInstanceOf("\Chevron\HTML\Element", $el, "Element::__construct (complex) failed to return an instace of Chevron\HTML\Element");

	}

	public function test_compound_constructor_instance(){
		try {
			$el = new \Chevron\HTML\Element("p", array("id" => "id", "class" => array("class1", "class2")));
		} catch (\Exception $e) {
			$this->fail($e->getMessage());
		}

		$this->assertInstanceOf("\Chevron\HTML\Element", $el, "Element::__construct (compound) failed to return an instace of Chevron\HTML\Element");

	}

	public function get_complex_instance(){
		$el = new \Chevron\HTML\Element("p#id.class1.class2[key=value]");
		return $el;
	}

	public function get_compound_instance(){
		$el = new \Chevron\HTML\Element("p", array("id" => "id", "class" => array("class1", "class2"), "key" => "value"));
		return $el;
	}

	/**
	 * @depends test_complex_constructor_instance
	 */
	public function test_complex_constructor_tag(){
		$el = $this->get_complex_instance();

		$reflection = new ReflectionClass($el);

		$tag = $reflection->getProperty("tag");
		$tag->setAccessible(true);
		$value = $tag->getValue($el);

		$this->assertEquals($value, "p", "Element::__construct (complex) failed to parse the correct tag");

	}

	/**
	 * @depends test_complex_constructor_instance
	 */
	public function test_complex_constructor_attributes(){
		$el = $this->get_complex_instance();

		$reflection = new ReflectionClass($el);

		$attributes = $reflection->getProperty("attributes");
		$attributes->setAccessible(true);
		$value = $attributes->getValue($el);

		$expected = array(
			"id"    => array("id"),
			"class" => array("class1", "class2"),
			"key"   => "value",
		);

		$this->assertEquals($expected, $value, "Element::__construct (complex) failed to parse the correct attributes");

	}

	/**
	 * @depends test_compound_constructor_instance
	 */
	public function test_compound_constructor_tag(){
		$el = $this->get_compound_instance();

		$reflection = new ReflectionClass($el);

		$tag = $reflection->getProperty("tag");
		$tag->setAccessible(true);
		$value = $tag->getValue($el);

		$this->assertEquals("p", $value, "Element::__construct (compound) failed to parse the correct tag");
	}

	/**
	 * @depends test_compound_constructor_instance
	 */
	public function test_compound_constructor_attributes(){
		$el = $this->get_compound_instance();

		$reflection = new ReflectionClass($el);

		$attributes = $reflection->getProperty("attributes");
		$attributes->setAccessible(true);
		$value = $attributes->getValue($el);

		$expected = array(
			"id"    => "id",
			"class" => array("class1", "class2"),
			"key"   => "value",
		);

		$this->assertEquals($expected, $value, "Element::__construct (compound) failed to parse the correct attributes");
	}

	public function test_parse_simple_selector(){
		$el = new \Chevron\HTML\Element("p");

		// simple
		$selector = "tag";
		list($tag, $attrs) = $el->parse_selector($selector);

		$expected = array(
			"id"    => array(),
			"class" => array(),
		);

		$this->assertEquals($tag, "tag", "Element::parse_selector (tag) failed to correctly parse a simple selector");
		$this->assertEquals($expected, $attrs, "Element::parse_selector (attrs) failed to correctly parse a simple selector");

	}

	public function test_parse_full_selector(){
		$el = new \Chevron\HTML\Element("p");

		// full
		$selector = "tag#id.class1.class2[key=val]~content";
		list($tag, $attrs) = $el->parse_selector($selector);

		$expected = array(
			"id"    => array("id"),
			"class" => array("class1", "class2"),
			"key"   => "val",
		);

		$this->assertEquals($tag, "tag", "Element::parse_selector (tag) failed to correctly parse a full selector");
		$this->assertEquals($expected, $attrs, "Element::parse_selector (attrs) failed to correctly parse a full selector");

	}

	public function test_parse_partial_selector(){

		$el = new \Chevron\HTML\Element("p");

		// partial
		$selector = "tag.class2[key=val]~content";
		list($tag, $attrs) = $el->parse_selector($selector);

		$expected = array(
			"id"    => array(),
			"class" => array("class2"),
			"key"   => "val",
		);

		$this->assertEquals($tag, "tag", "Element::parse_selector (tag) failed to correctly parse a partial selector");
		$this->assertEquals($expected, $attrs, "Element::parse_selector (attrs) failed to correctly parse a partial selector");

	}

	public function test_no_attrs(){
		$el = new \Chevron\HTML\Element("p");
		$reflection = new ReflectionClass($el);

		$property = $reflection->getProperty("attributes");
		$property->setAccessible(true);
		$attributes = $property->getValue($el);

		$this->assertInternalType("array", $attributes, "Element::attrs default \$attributes is the wrong type");
		$this->assertEquals($attributes, array(), "Element::attrs default \$attributes has the wrong value");

	}

	public function test_attrs(){
		$el = new \Chevron\HTML\Element("p");
		$reflection = new ReflectionClass($el);

		$property = $reflection->getProperty("attributes");
		$property->setAccessible(true);

		$data = array(
			"id" => "ELID",
			"class" => array("class1", "class2"),
			"data-mydata" => "is some data",
		);

		$el->attrs($data);
		$attributes = $property->getValue($el);

		$this->assertEquals($attributes["id"], "ELID", "Element::attrs failed to set data properly");
		$this->assertEquals($attributes["class"], array("class1", "class2"), "Element::attrs failed to set data properly");
		$this->assertEquals($attributes["data-mydata"], "is some data", "Element::attrs failed to set data properly");

	}

	public function test_no_innerHTML(){
		$el = new \Chevron\HTML\Element("p");
		$reflection = new ReflectionClass($el);

		$property = $reflection->getProperty("innerHTML");
		$property->setAccessible(true);
		$innerHTML = $property->getValue($el);

		$this->assertInternalType("null", $innerHTML, "Element::innerHTML is not null by default");

	}

	public function test_innerHTML(){
		$el = new \Chevron\HTML\Element("p");
		$reflection = new ReflectionClass($el);

		$property = $reflection->getProperty("innerHTML");
		$property->setAccessible(true);

		$data = "This is a HUGE string of HTML!!";

		$el->innerHTML($data);
		$innerHTML = $property->getValue($el);

		$this->assertEquals($innerHTML, "This is a HUGE string of HTML!!", "Element::innerHTML failed to set data properly");

	}

	/**
	 * @depends test_no_innerHTML
	 */
	public function test_entitiy_innerHTML(){
		$el = new \Chevron\HTML\Element("p");
		$reflection = new ReflectionClass($el);

		$property = $reflection->getProperty("innerHTML");
		$property->setAccessible(true);

		$data = "This is a HUGE string of HTML!! and <script type=\"text/javascript\">alert('BOOM')</script> JAVASCRIPT!!!! OH MY!!!!!";

		$el->innerHTML($data);
		$innerHTML = $property->getValue($el);

		$expected = "This is a HUGE string of HTML!! and &lt;script type=&quot;text/javascript&quot;&gt;alert(&#039;BOOM&#039;)&lt;/script&gt; JAVASCRIPT!!!! OH MY!!!!!";
		$this->assertEquals($expected, $innerHTML, "Element::innerHTML failed to convert entities properly");

	}

	public function test_stringify_simple_attrs(){

		$data = array(
			"id"    => "id",
			"class" => "class1",
			"key"   => "val",
		);

		$result = \Chevron\HTML\Element::stringify_attrs($data);

		$expected = 'id="id" class="class1" key="val"';

		$this->assertEquals($expected, $result, "Element::stringify_attrs failed to stringify (simple) correctly");

	}

	public function test_stringify_complex_attrs(){

		$data = array(
			"id"    => array("id"),
			"class" => array("class1", "class2"),
			"key"   => "val",
		);

		$result = \Chevron\HTML\Element::stringify_attrs($data);

		$expected = 'id="id" class="class1 class2" key="val"';

		$this->assertEquals($expected, $result, "Element::stringify_attrs failed to stringify (compound) correctly");

	}

	public function test_stringify_entity_simple_attrs(){

		$data = array(
			"id"    => "id",
			"class" => "cla<br />ss1",
			"key"   => "va<script type=\"text/javascript\">alert('BOOM')</script>l",
		);

		$result = \Chevron\HTML\Element::stringify_attrs($data);

		$expected = 'id="id" class="cla&lt;br /&gt;ss1" key="va&lt;script type=&quot;text/javascript&quot;&gt;alert(&#039;BOOM&#039;)&lt;/script&gt;l"';

		$this->assertEquals($expected, $result, "Element::stringify_attrs failed to stringify (simple entitiy) correctly");

	}

	public function test_stringify_entity_complex_attrs(){

		$data = array(
			"id"    => array("id"),
			"class" => array("cla<br />ss1", "class2"),
			"key"   => "va<script type=\"text/javascript\">alert('BOOM')</script>l",
		);

		$result = \Chevron\HTML\Element::stringify_attrs($data);

		$expected = 'id="id" class="cla&lt;br /&gt;ss1 class2" key="va&lt;script type=&quot;text/javascript&quot;&gt;alert(&#039;BOOM&#039;)&lt;/script&gt;l"';

		$this->assertEquals($expected, $result, "Element::stringify_attrs failed to stringify (compound entitiy) correctly");

	}

	/**
	 * @depends test_complex_constructor_instance
	 */
	public function test_magic_to_string_initial_selector_no_content(){
		$el = new \Chevron\HTML\Element("p#First.other[data-lang=es_mx]");

		$result = (string)$el;
		$expected = '<p id="First" class="other" data-lang="es_mx"></p>';

		$this->assertEquals($expected, $result, "Element::__toString (initial selector no content) failed to correctly stringify itself");

	}

	/**
	 * @depends test_complex_constructor_instance
	 */
	public function test_magic_to_string_initial_selector_content(){
		$el = new \Chevron\HTML\Element("p#First.other[data-lang=es_mx]");

		$el->innerHTML("This is paragraph text.");
		$result = (string)$el;
		$expected = '<p id="First" class="other" data-lang="es_mx">This is paragraph text.</p>';

		$this->assertEquals($expected, $result, "Element::__toString (initial selector content) failed to correctly stringify itself");

	}

	/**
	 * @depends test_compound_constructor_instance
	 */
	public function test_magic_to_string_initial_attrs_no_content(){
		$el = new \Chevron\HTML\Element("p", array("id" => "First", "class" => "other", "data-lang" => "es_mx"));

		$result = (string)$el;
		$expected = '<p id="First" class="other" data-lang="es_mx"></p>';

		$this->assertEquals($expected, $result, "Element::__toString (initial selector/attrs no content) failed to correctly stringify itself");

	}

	/**
	 * @depends test_compound_constructor_instance
	 */
	public function test_magic_to_string_initial_attrs_content(){
		$el = new \Chevron\HTML\Element("p", array("id" => "First", "class" => "other", "data-lang" => "es_mx"));

		$el->innerHTML("This is paragraph text.");
		$result = (string)$el;
		$expected = '<p id="First" class="other" data-lang="es_mx">This is paragraph text.</p>';

		$this->assertEquals($expected, $result, "Element::__toString (initial selector/attrs content) failed to correctly stringify itself");

	}

	/**
	 * @depends test_simple_constructor
	 * @depends test_attrs
	 */
	public function test_magic_to_string_attrs_no_content(){
		$el = new \Chevron\HTML\Element("p");
		$el->attrs(array("id" => "First", "class" => "other", "data-lang" => "es_mx", "disabled"));

		$result = (string)$el;
		$expected = '<p id="First" class="other" data-lang="es_mx" disabled></p>';

		$this->assertEquals($expected, $result, "Element::__toString (initial selector function attrs no content) failed to correctly stringify itself");

	}

	/**
	 * @depends test_simple_constructor
	 */
	public function test_magic_to_string_attrs_content(){
		$el = new \Chevron\HTML\Element("p");

		$el->innerHTML("This is paragraph text.");
		$result = (string)$el;
		$expected = '<p>This is paragraph text.</p>';

		$this->assertEquals($expected, $result, "Element::__toString (initial selector function attrs content) failed to correctly stringify itself");

	}

	/**
	 * @depends test_simple_constructor
	 * @depends test_innerHTML
	 */
	public function test_magic_to_string_self_closing_tag(){
		$el = new \Chevron\HTML\Element("br");

		$result = (string)$el;
		$expected = '<br />';

		$this->assertEquals($expected, $result, "Element::__toString (self closing tag) failed to correctly stringify itself");

	}

	/**
	 * @depends test_simple_constructor
	 * @depends test_innerHTML
	 */
	public function test_magic_to_string_self_closing_tag_reject_content(){
		$el = new \Chevron\HTML\Element("br");

		$el->innerHTML("This is paragraph text.");
		$result = (string)$el;
		$expected = '<br />';

		$this->assertEquals($expected, $result, "Element::__toString (self closing tag, reject content) failed to correctly stringify itself");

	}

	/**
	 * @depends test_simple_constructor
	 * @depends test_attrs
	 * @depends test_innerHTML
	 */
	public function test_magic_to_string_empty_tag(){
		$el = new \Chevron\HTML\Element("param");
		$el->attrs(array("id" => "First", "class" => "other", "data-lang" => "es_mx"));

		$result = (string)$el;
		$expected = '<param id="First" class="other" data-lang="es_mx"></param>';

		$this->assertEquals($expected, $result, "Element::__toString (empty tag) failed to correctly stringify itself");

	}

	/**
	 * @depends test_simple_constructor
	 * @depends test_attrs
	 * @depends test_innerHTML
	 */
	public function test_magic_to_string_empty_tag_reject_content(){
		$el = new \Chevron\HTML\Element("param");

		$el->innerHTML("This is paragraph text.");
		$result = (string)$el;
		$expected = '<param></param>';

		$this->assertEquals($expected, $result, "Element::__toString (empty tag reject content) failed to correctly stringify itself");

	}

}

