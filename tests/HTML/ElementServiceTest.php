<?php

require_once("tests/bootstrap.php");

use \Chevron\HTML;

FUnit::test("ElementService::__call()", function(){
	$elF = new HTML\ElementService;
	$el = $elF->p("This is html.");
	FUnit::equal("<p>This is html.</p>", (string)$el);
});

FUnit::test("ElementService::__call() w/ escaping", function(){
	$elF = new HTML\ElementService;
	$el = $elF->p("This is html.");
	$el->setInnerHTML("This is <new>html</new>.");
	$el->setAttributes(array(
		"class" => "testClass",
	));
	FUnit::equal("<p class=\"testClass\">This is &lt;new&gt;html&lt;/new&gt;.</p>", (string)$el);

});

FUnit::test("ElementService::__call() input w/ escaping", function(){
	$elF = new HTML\ElementService;
	$el = $elF->text("textName", "a <bad>textValue</bad>");
	$el->setAttributes(array(
		"class" => "testClass",
	));
	$expected = "<input type=\"text\" name=\"textName\" value=\"a &lt;bad&gt;textValue&lt;/bad&gt;\" class=\"testClass\" />";
	FUnit::equal($expected, (string)$el);

});

FUnit::test("ElementService::__call() radio w/ escaping", function(){
	$elF = new HTML\ElementService;
	$el = $elF->radio("textName", "a <bad>textValue</bad>", true);
	$expected = "<input type=\"radio\" name=\"textName\" value=\"a &lt;bad&gt;textValue&lt;/bad&gt;\" checked=\"checked\" />";
	FUnit::equal($expected, (string)$el);

});

FUnit::test("ElementService::select()", function(){

	$elF = new HTML\ElementService;

	$options = array(
		"5"  => "five",
		"15" => "fifteen",
		"25" => "twenty-five",
		"35" => "thirty-five",
	);

	$selected = 5;

	$attributes = array(
		"class" => "select-options-class"
	);

	$result = $elF->select("name", $options, $selected, $attributes);

	$expected = '<select name="name" class="select-options-class">'
					.'<option value="5" selected="selected">five</option>'
					.'<option value="15">fifteen</option>'
					.'<option value="25">twenty-five</option>'
					.'<option value="35">thirty-five</option>'
				.'</select>';

	FUnit::equal($result, $expected);

});

FUnit::test("ElementService::select() w/o selection", function(){

	$elF = new HTML\ElementService;

	$options = array(
		"5"  => "five",
		"15" => "fifteen",
		"25" => "twenty-five",
		"35" => "thirty-five",
	);

	$selected = "";

	$attributes = array(
		"class" => "select-options-class"
	);

	$selected = "";

	$result = $elF->select("name", $options, $selected, $attributes);

	$expected = '<select name="name" class="select-options-class">'
					.'<option value="5">five</option>'
					.'<option value="15">fifteen</option>'
					.'<option value="25">twenty-five</option>'
					.'<option value="35">thirty-five</option>'
				.'</select>';

	FUnit::equal($result, $expected);

});

FUnit::test("ElementService::select() w/ multi selection", function(){

	$elF = new HTML\ElementService;

	$options = array(
		"5"  => "five",
		"15" => "fifteen",
		"25" => "twenty-five",
		"35" => "thirty-five",
	);

	$selected = array(5, 25);

	$attributes = array(
		"multiple",
		"size" => 4
	);

	$result = $elF->select("name", $options, $selected, $attributes);

	$expected = '<select name="name" multiple size="4">'
					.'<option value="5" selected="selected">five</option>'
					.'<option value="15">fifteen</option>'
					.'<option value="25" selected="selected">twenty-five</option>'
					.'<option value="35">thirty-five</option>'
				.'</select>';

	FUnit::equal($result, $expected);

});

FUnit::test("ElementService::select() w/ nothing", function(){

	$elF = new HTML\ElementService;

	$options = array(
		"5"  => "five",
		"15" => "fifteen",
		"25" => "twenty-five",
		"35" => "thirty-five",
	);

	$selected = array();

	$attributes = array();

	$result = $elF->select("name", $options, $selected, $attributes);

	$expected = '<select name="name">'
					.'<option value="5">five</option>'
					.'<option value="15">fifteen</option>'
					.'<option value="25">twenty-five</option>'
					.'<option value="35">thirty-five</option>'
				.'</select>';

	FUnit::equal($result, $expected);

});

FUnit::test("ElementService::select() w/ optgroups w/ single selection", function(){

	$elF = new HTML\ElementService;

	$options = array(
		"first" => array(
			"5"  => "five",
			"15" => "fifteen",
			"25" => "twenty-five",
			"35" => "thirty-five",
		),
		"second" => array(
			"50"  => "five",
			"150" => "fifteen",
			"250" => "twenty-five",
			"350" => "thirty-five",
		),
		"third" => array(
			"500"  => "five",
			"1500" => "fifteen",
			"2500" => "twenty-five",
			"3500" => "thirty-five",
		),
	);

	$selected = 5;

	$attributes = array(
		"class" => "select-options-class"
	);

	$result = $elF->select("name", $options, $selected, $attributes);

	$expected = '<select name="name" class="select-options-class">'
					.'<optgroup label="first">'
						.'<option value="5" selected="selected">five</option>'
						.'<option value="15">fifteen</option>'
						.'<option value="25">twenty-five</option>'
						.'<option value="35">thirty-five</option>'
					.'</optgroup>'
					.'<optgroup label="second">'
						.'<option value="50">five</option>'
						.'<option value="150">fifteen</option>'
						.'<option value="250">twenty-five</option>'
						.'<option value="350">thirty-five</option>'
					.'</optgroup>'
					.'<optgroup label="third">'
						.'<option value="500">five</option>'
						.'<option value="1500">fifteen</option>'
						.'<option value="2500">twenty-five</option>'
						.'<option value="3500">thirty-five</option>'
					.'</optgroup>'
				.'</select>';

	FUnit::equal($result, $expected);

});

FUnit::test("ElementService::select() w/ optgroups w/o selection", function(){

	$elF = new HTML\ElementService;

	$options = array(
		"first" => array(
			"5"  => "five",
			"15" => "fifteen",
			"25" => "twenty-five",
			"35" => "thirty-five",
		),
		"second" => array(
			"50"  => "five",
			"150" => "fifteen",
			"250" => "twenty-five",
			"350" => "thirty-five",
		),
		"third" => array(
			"500"  => "five",
			"1500" => "fifteen",
			"2500" => "twenty-five",
			"3500" => "thirty-five",
		),
	);

	$selected = "";

	$attributes = array(
		"class" => "select-options-class"
	);

	$result = $elF->select("name", $options, $selected, $attributes);

	$expected = '<select name="name" class="select-options-class">'
					.'<optgroup label="first">'
						.'<option value="5">five</option>'
						.'<option value="15">fifteen</option>'
						.'<option value="25">twenty-five</option>'
						.'<option value="35">thirty-five</option>'
					.'</optgroup>'
					.'<optgroup label="second">'
						.'<option value="50">five</option>'
						.'<option value="150">fifteen</option>'
						.'<option value="250">twenty-five</option>'
						.'<option value="350">thirty-five</option>'
					.'</optgroup>'
					.'<optgroup label="third">'
						.'<option value="500">five</option>'
						.'<option value="1500">fifteen</option>'
						.'<option value="2500">twenty-five</option>'
						.'<option value="3500">thirty-five</option>'
					.'</optgroup>'
				.'</select>';

	FUnit::equal($result, $expected);

});

FUnit::test("ElementService::select() w/ optgroups w multi selection", function(){

	$elF = new HTML\ElementService;

	$options = array(
		"first" => array(
			"5"  => "five",
			"15" => "fifteen",
			"25" => "twenty-five",
			"35" => "thirty-five",
		),
		"second" => array(
			"50"  => "five",
			"150" => "fifteen",
			"250" => "twenty-five",
			"350" => "thirty-five",
		),
		"third" => array(
			"500"  => "five",
			"1500" => "fifteen",
			"2500" => "twenty-five",
			"3500" => "thirty-five",
		),
	);

	$selected = array(5, 25, 2500);

	$attributes = array(
		"multiple",
		"size" => 4
	);

	$result = $elF->select("name", $options, $selected, $attributes);

	$expected = '<select name="name" multiple size="4">'
					.'<optgroup label="first">'
						.'<option value="5" selected="selected">five</option>'
						.'<option value="15">fifteen</option>'
						.'<option value="25" selected="selected">twenty-five</option>'
						.'<option value="35">thirty-five</option>'
					.'</optgroup>'
					.'<optgroup label="second">'
						.'<option value="50">five</option>'
						.'<option value="150">fifteen</option>'
						.'<option value="250">twenty-five</option>'
						.'<option value="350">thirty-five</option>'
					.'</optgroup>'
					.'<optgroup label="third">'
						.'<option value="500">five</option>'
						.'<option value="1500">fifteen</option>'
						.'<option value="2500" selected="selected">twenty-five</option>'
						.'<option value="3500">thirty-five</option>'
					.'</optgroup>'
				.'</select>';

	FUnit::equal($result, $expected);

});
