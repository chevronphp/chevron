<?php


FUnit::test("Form::__callStatic() w/o args", function(){

	$el = \Chevron\HTML\Form::text("name");

	if(!($el InstanceOf \Chevron\HTML\Form)){
		FUnit::fail("incorrect InstanceOf");
	}

	$reflection = new ReflectionClass($el);

	$tag = $reflection->getProperty("tag");
	$tag->setAccessible(true);
	$value = $tag->getValue($el);

	FUnit::equal($value, "input", "correct tag");

	$attributes = $reflection->getProperty("attributes");
	$attributes->setAccessible(true);
	$value = $attributes->getValue($el);
	$expected = array("name" => "name", "type" => "text");

	FUnit::equal($value, $expected, "correct attrs");

});


FUnit::test("Form::__callStatic() w/ args", function(){

	$el = \Chevron\HTML\Form::radio("name", "value", true, array("data-test" => "15"));

	if(!($el InstanceOf \Chevron\HTML\Form)){
		FUnit::fail("incorrect InstanceOf");
	}

	$reflection = new ReflectionClass($el);

	$attributes = $reflection->getProperty("attributes");
	$attributes->setAccessible(true);
	$value = $attributes->getValue($el);
	$expected = array("name" => "name", "type" => "radio", "checked" => "checked", "value" => "value", "data-test" => "15");

	FUnit::equal($value, $expected, "correct attrs");

});

FUnit::test("Form::__callStatic() w/ innerHTML", function(){

	$el = \Chevron\HTML\Form::textarea("name", "This is the innerHTML", false, array("rows" => "15", "cols" => "15"));

	$reflection = new ReflectionClass($el);

	$innerHTML = $reflection->getProperty("innerHTML");
	$innerHTML->setAccessible(true);
	$value = $innerHTML->getValue($el);
	$expected = "This is the innerHTML";

	FUnit::equal($value, $expected);

});

FUnit::test("Form::arrayify_name()", function(){

	$data = array("this", "is", "an", "array");
	$result   = \Chevron\HTML\Form::arrayify_name($data);
	$expected = "this[is][an][array]";

	FUnit::equal($result, $expected, "w/o a numeric key");

	$data = array("this", "", "an", "array");
	$result   = \Chevron\HTML\Form::arrayify_name($data);
	$expected = "this[][an][array]";

	FUnit::equal($result, $expected, "w/ a numeric key");

});


FUnit::test("Form::select() w/o groups w/ all params", function(){
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

	$result = \Chevron\HTML\Form::select("name", $options, $selected, $attributes);

	$expected = '<select name="name" class="select-options-class">'
					.'<option value="5" selected="selected">five</option>'
					.'<option value="15">fifteen</option>'
					.'<option value="25">twenty-five</option>'
					.'<option value="35">thirty-five</option>'
				.'</select>';

	FUnit::equal($result, $expected);
});

FUnit::test("Form::select() w/o groups w/o a selected option", function(){
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

	$result = \Chevron\HTML\Form::select("name", $options, $selected, $attributes);

	$expected = '<select name="name" class="select-options-class">'
					.'<option value="5">five</option>'
					.'<option value="15">fifteen</option>'
					.'<option value="25">twenty-five</option>'
					.'<option value="35">thirty-five</option>'
				.'</select>';

	FUnit::equal($result, $expected);
});

FUnit::test("Form::select() w/o groups w/ multiple selected options", function(){
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

	$result = \Chevron\HTML\Form::select("name", $options, $selected, $attributes);

	$expected = '<select name="name" multiple size="4">'
					.'<option value="5" selected="selected">five</option>'
					.'<option value="15">fifteen</option>'
					.'<option value="25" selected="selected">twenty-five</option>'
					.'<option value="35">thirty-five</option>'
				.'</select>';

	FUnit::equal($result, $expected);
});

FUnit::test("Form::select() w/o groups w/o selection w/o attrs", function(){
	$options = array(
		"5"  => "five",
		"15" => "fifteen",
		"25" => "twenty-five",
		"35" => "thirty-five",
	);

	$selected = array();

	$attributes = array();

	$result = \Chevron\HTML\Form::select("name", $options, $selected, $attributes);

	$expected = '<select name="name">'
					.'<option value="5">five</option>'
					.'<option value="15">fifteen</option>'
					.'<option value="25">twenty-five</option>'
					.'<option value="35">thirty-five</option>'
				.'</select>';

	FUnit::equal($result, $expected);
});

FUnit::test("Form::select() w/ groups w/ all params", function(){
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

	$result = \Chevron\HTML\Form::select("name", $options, $selected, $attributes);

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

FUnit::test("Form::select() w/ groups w/o a selected option", function(){
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

	$result = \Chevron\HTML\Form::select("name", $options, $selected, $attributes);

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

FUnit::test("Form::select() w/ groups w/ multiple selected options", function(){
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

	$result = \Chevron\HTML\Form::select("name", $options, $selected, $attributes);

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


