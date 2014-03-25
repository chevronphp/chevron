<?php

FUnit::test("Element::__construct() with a simple tag", function(){

	$el = new \Chevron\HTML\Element("p");

	if($el InstanceOf \Chevron\HTML\Element){
		FUnit::ok(1);
	}else{
		FUnit::fail("Instantiation failed");
	}

});

FUnit::test("Element::__construct() with a selector string", function(){

	$el = new \Chevron\HTML\Element("p#id.class1.class2[key=value]");

	if($el InstanceOf \Chevron\HTML\Element){
		FUnit::ok(1, "Instantiation passed");
	}else{
		FUnit::fail("Instantiation failed");
	}

	$reflection = new ReflectionClass($el);

	$tag = $reflection->getProperty("tag");
	$tag->setAccessible(true);
	$value = $tag->getValue($el);

	FUnit::equal($value, "p", "failed to parse for the correct tag");

	$attributes = $reflection->getProperty("attributes");
	$attributes->setAccessible(true);
	$value = $attributes->getValue($el);

	$expected = array(
		"id"    => array("id"),
		"class" => array("class1", "class2"),
		"key"   => "value",
	);

	FUnit::equal($expected, $value, "failed to parse for the correct attributes");

});

FUnit::test("Element::__construct() with a simple tag and an attr map", function(){

	$el = new \Chevron\HTML\Element("p", array("id" => "id", "class" => array("class1", "class2"), "key" => "value"));

	if($el InstanceOf \Chevron\HTML\Element){
		FUnit::ok(1, "Instantiation passed");
	}else{
		FUnit::fail("Instantiation failed");
	}

	$reflection = new ReflectionClass($el);

	$tag = $reflection->getProperty("tag");
	$tag->setAccessible(true);
	$value = $tag->getValue($el);

	FUnit::equal($value, "p", "failed to parse for the correct tag");

	$attributes = $reflection->getProperty("attributes");
	$attributes->setAccessible(true);
	$value = $attributes->getValue($el);

	$expected = array(
		"id"    => "id",
		"class" => array("class1", "class2"),
		"key"   => "value",
	);

	FUnit::equal($expected, $value, "failed to parse for the correct attributes");

});

FUnit::test("Element::parse_selector() w/o attrs", function(){
	$el = new \Chevron\HTML\Element("p");

	// simple
	$selector = "tag";
	list($tag, $attrs) = $el->parse_selector($selector);

	$expected = array(
		"id"    => array(),
		"class" => array(),
	);

	FUnit::equal($tag, "tag", "correct tag");
	FUnit::equal($expected, $attrs, "correctly empty attrs");

});

FUnit::test("Element::parse_selector() w/ full attrs", function(){
	$el = new \Chevron\HTML\Element("p");

	// full
	$selector = "tag#id.class1.class2[key=val]~content";
	list($tag, $attrs) = $el->parse_selector($selector);

	$expected = array(
		"id"    => array("id"),
		"class" => array("class1", "class2"),
		"key"   => "val",
	);

	FUnit::equal($tag, "tag", "correct tag");
	FUnit::equal($expected, $attrs, "correct attrs");

});

FUnit::test("Element::parse_selector() w/ partial attrs", function(){
	$el = new \Chevron\HTML\Element("p");

	// partial
	$selector = "tag.class2[key=val]~content";
	list($tag, $attrs) = $el->parse_selector($selector);

	$expected = array(
		"id"    => array(),
		"class" => array("class2"),
		"key"   => "val",
	);

	FUnit::equal($tag, "tag", "correct tag");
	FUnit::equal($expected, $attrs, "correct attrs");

});

FUnit::test("Element::\$attrs default type and value", function(){
	$el = new \Chevron\HTML\Element("p");
	$reflection = new ReflectionClass($el);

	$property = $reflection->getProperty("attributes");
	$property->setAccessible(true);
	$attributes = $property->getValue($el);

	FUnit::ok(is_array($attributes), "is array");
	FUnit::ok(empty($attributes), "is empty");

});

FUnit::test("Element::\$innerHTML default type and value", function(){
	$el = new \Chevron\HTML\Element("p");
	$reflection = new ReflectionClass($el);

	$property = $reflection->getProperty("innerHTML");
	$property->setAccessible(true);
	$innerHTML = $property->getValue($el);

	FUnit::ok(is_null($innerHTML), "NULL");

});

FUnit::test("Element::attrs() correctly sets values", function(){
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

	FUnit::equal($attributes["id"], "ELID", "set attr value");
	FUnit::equal($attributes["class"], array("class1", "class2"), "set attr value and type");
	FUnit::equal($attributes["data-mydata"], "is some data", "set attr value");

});

FUnit::test("Element::innerHTML() correctly sets and entitiy-izes value", function(){
	$el = new \Chevron\HTML\Element("p");
	$reflection = new ReflectionClass($el);

	$property = $reflection->getProperty("innerHTML");
	$property->setAccessible(true);
	$data = "This is a HUGE string of HTML!!";
	$el->innerHTML($data);
	$innerHTML = $property->getValue($el);

	FUnit::equal($innerHTML, "This is a HUGE string of HTML!!", "w/o entities");

	$data = "This is a HUGE string of HTML!! and <script type=\"text/javascript\">alert('BOOM')</script> JAVASCRIPT!!!! OH MY!!!!!";
	$el->innerHTML($data);
	$innerHTML = $property->getValue($el);
	$expected = "This is a HUGE string of HTML!! and &lt;script type=&quot;text/javascript&quot;&gt;alert(&#039;BOOM&#039;)&lt;/script&gt; JAVASCRIPT!!!! OH MY!!!!!";

	FUnit::equal($expected, $innerHTML, "w/ entities");

});

FUnit::test("Element::stringify_attrs() scalar values", function(){
	$data = array(
		"id"    => "id",
		"class" => "class1",
		"key"   => "val",
	);

	$result = \Chevron\HTML\Element::stringify_attrs($data);

	$expected = 'id="id" class="class1" key="val"';

	FUnit::equal($expected, $result, "w/o entities");

	$data = array(
		"id"    => "id",
		"class" => "cla<br />ss1",
		"key"   => "va<script type=\"text/javascript\">alert('BOOM')</script>l",
	);

	$result = \Chevron\HTML\Element::stringify_attrs($data);

	$expected = 'id="id" class="cla&lt;br /&gt;ss1" key="va&lt;script type=&quot;text/javascript&quot;&gt;alert(&#039;BOOM&#039;)&lt;/script&gt;l"';

	FUnit::equal($expected, $result, "w/ entities");
});

FUnit::test("Element::stringify_attrs() non-scalar values", function(){
	$data = array(
		"id"    => array("id"),
		"class" => array("class1", "class2"),
		"key"   => "val",
	);

	$result = \Chevron\HTML\Element::stringify_attrs($data);

	$expected = 'id="id" class="class1 class2" key="val"';

	FUnit::equal($expected, $result, "w/o entities");

	$data = array(
		"id"    => array("id"),
		"class" => array("cla<br />ss1", "class2"),
		"key"   => "va<script type=\"text/javascript\">alert('BOOM')</script>l",
	);

	$result = \Chevron\HTML\Element::stringify_attrs($data);

	$expected = 'id="id" class="cla&lt;br /&gt;ss1 class2" key="va&lt;script type=&quot;text/javascript&quot;&gt;alert(&#039;BOOM&#039;)&lt;/script&gt;l"';

	FUnit::equal($expected, $result, "w/ entities");
});

FUnit::test("Element::__toString() after each __constructor type", function(){

	$el = new \Chevron\HTML\Element("p#First.other[data-lang=es_mx]");
	$result = (string)$el;
	$expected = '<p id="First" class="other" data-lang="es_mx"></p>';

	FUnit::equal($expected, $result, "w/ selector string w/o content");

	$el = new \Chevron\HTML\Element("p#First.other[data-lang=es_mx]");
	$el->innerHTML("This is paragraph text.");
	$result = (string)$el;
	$expected = '<p id="First" class="other" data-lang="es_mx">This is paragraph text.</p>';

	FUnit::equal($expected, $result, "w/ selector string and content");

	$el = new \Chevron\HTML\Element("p", array("id" => "First", "class" => "other", "data-lang" => "es_mx"));
	$result = (string)$el;
	$expected = '<p id="First" class="other" data-lang="es_mx"></p>';

	FUnit::equal($expected, $result, "w/ attr map w/o content");

	$el = new \Chevron\HTML\Element("p", array("id" => "First", "class" => "other", "data-lang" => "es_mx"));
	$el->innerHTML("This is paragraph text.");
	$result = (string)$el;
	$expected = '<p id="First" class="other" data-lang="es_mx">This is paragraph text.</p>';

	FUnit::equal($expected, $result, "w/ attr map and content");

});

FUnit::test("Element::__toString() setting attrs after __construction", function(){

	$el = new \Chevron\HTML\Element("p");
	$el->attrs(array("id" => "First", "class" => "other", "data-lang" => "es_mx", "disabled"));
	$result = (string)$el;
	$expected = '<p id="First" class="other" data-lang="es_mx" disabled></p>';

	FUnit::equal($expected, $result, "w/ attrs w/o content");

	$el = new \Chevron\HTML\Element("p");
	$el->innerHTML("This is paragraph text.");
	$result = (string)$el;
	$expected = '<p>This is paragraph text.</p>';

	FUnit::equal($expected, $result, "w/ attrs and content");

});

FUnit::test("Element::__toString() on a self-closing tag (e.g. <br />)", function(){

		$el = new \Chevron\HTML\Element("br");
		$result = (string)$el;
		$expected = '<br />';

		FUnit::equal($expected, $result, "w/o content");

		$el = new \Chevron\HTML\Element("br");
		$el->innerHTML("This is paragraph text.");
		$result = (string)$el;
		$expected = '<br />';

		FUnit::equal($expected, $result, "rejects content");

});

FUnit::test("Element::__toString() on an empty tag (e.g. <param></param>)", function(){

		$el = new \Chevron\HTML\Element("param");
		$el->attrs(array("id" => "First", "class" => "other", "data-lang" => "es_mx"));
		$result = (string)$el;
		$expected = '<param id="First" class="other" data-lang="es_mx"></param>';

		FUnit::equal($expected, $result, "w/o content");


		$el = new \Chevron\HTML\Element("param");
		$el->innerHTML("This is paragraph text.");
		$result = (string)$el;
		$expected = '<param></param>';

		FUnit::equal($expected, $result, "rejects content");

});

