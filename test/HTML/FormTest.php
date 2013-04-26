<?php

class FormTest extends PHPUnit_Framework_TestCase {

	public function getDependencyMessage($err){
		$msg = "{$err}\n\n###\nThe Form class is built on the Element class and since this one failed"
				." it might also be worth your time checking the tests for the Element class.";
		return $msg;
	}

	public function test_simple_instance(){

		$el = \Chevron\HTML\Form::text("name");

		$this->assertInstanceOf("\Chevron\HTML\Form", $el, "Form::__callStatic failed to return the correct instance type");

	}

	/**
	 * @depends test_simple_instance
	 */
	public function test_simple_tag(){

		$el = \Chevron\HTML\Form::text("name");

		$reflection = new ReflectionClass($el);

		$tag = $reflection->getProperty("tag");
		$tag->setAccessible(true);
		$value = $tag->getValue($el);

		$this->assertEquals($value, "input", $this->getDependencyMessage("Form::__callStatic failed to set the correct tag"));

	}

	/**
	 * @depends test_simple_instance
	 */
	public function test_simple_attributes(){

		$el = \Chevron\HTML\Form::text("name");

		$reflection = new ReflectionClass($el);

		$attributes = $reflection->getProperty("attributes");
		$attributes->setAccessible(true);
		$value = $attributes->getValue($el);
		$expected = array("name" => "name", "type" => "text");

		$this->assertEquals($value, $expected, $this->getDependencyMessage("Form::__callStatic failed to set the correct default attributes"));

	}

	/**
	 * @depends test_simple_instance
	 */
	public function test_less_simple_attributes(){

		$el = \Chevron\HTML\Form::radio("name", "value", true);

		$reflection = new ReflectionClass($el);

		$attributes = $reflection->getProperty("attributes");
		$attributes->setAccessible(true);
		$value = $attributes->getValue($el);
		$expected = array("name" => "name", "type" => "radio", "checked" => "checked", "value" => "value");

		$this->assertEquals($value, $expected, $this->getDependencyMessage("Form::__callStatic failed to set the correct default attributes"));

	}

	/**
	 * @depends test_simple_instance
	 */
	public function test_less_simple_still_attributes(){

		$el = \Chevron\HTML\Form::radio("name", "value", true, array("data-test" => "15"));

		$reflection = new ReflectionClass($el);

		$attributes = $reflection->getProperty("attributes");
		$attributes->setAccessible(true);
		$value = $attributes->getValue($el);
		$expected = array("name" => "name", "type" => "radio", "checked" => "checked", "value" => "value", "data-test" => "15");

		$this->assertEquals($value, $expected, $this->getDependencyMessage("Form::__callStatic failed to set the correct attributes"));

	}

	/**
	 * @depends test_simple_instance
	 */
	public function test_value_to_innerHTML(){

		$el = \Chevron\HTML\Form::textarea("name", "This is the innerHTML", false, array("rows" => "15", "cols" => "15"));

		$reflection = new ReflectionClass($el);

		$innerHTML = $reflection->getProperty("innerHTML");
		$innerHTML->setAccessible(true);
		$value = $innerHTML->getValue($el);
		$expected = "This is the innerHTML";

		$this->assertEquals($value, $expected, "Form::__callStatic failed to set the correct innerHTML");

	}

	public function test_arrayify_name_assoc(){

		$data = array("this", "is", "an", "array");

		$result   = \Chevron\HTML\Form::arrayify_name($data);
		$expected = "this[is][an][array]";

		$this->assertEquals($result, $expected, "Form::arrayify_name failed ot properly construct a name");
	}

	public function test_arrayify_name_numeric(){

		$data = array("this", "is", "an", "array");

		$data = array("this", "", "an", "array");

		$result   = \Chevron\HTML\Form::arrayify_name($data);
		$expected = "this[][an][array]";

		$this->assertEquals($result, $expected, "Form::arrayify_name failed ot properly construct a name");
	}

	/**
	 * @depends test_simple_instance
	 */
	public function test_select_all_params(){

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

		$this->assertEquals($result, $expected, "Form::select failed to create a proper selection ... variation 1");

	}

	/**
	 * @depends test_simple_instance
	 */
	public function test_select_no_selection(){

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

		$this->assertEquals($result, $expected, "Form::select failed to create a proper selection ... variation 2");

	}

	/**
	 * @depends test_simple_instance
	 */
	public function test_select_multi_selection(){

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

		$this->assertEquals($result, $expected, "Form::select failed to create a proper selection ... variation 3");

	}

	/**
	 * @depends test_simple_instance
	 */
	public function test_select_no_selection_no_attributes(){

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

		$this->assertEquals($result, $expected, "Form::select failed to create a proper selection ... variation 4");

	}

	/**
	 * @depends test_simple_instance
	 */
	public function test_select_groups_all_params(){

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

		$this->assertEquals($result, $expected, "Form::select (optgroups) failed to create a proper selection ... variation 1");

	}

	/**
	 * @depends test_simple_instance
	 */
	public function test_select_groups_no_selection(){

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

		$this->assertEquals($result, $expected, "Form::select (optgroups) failed to create a proper selection ... variation 2");

	}

	/**
	 * @depends test_simple_instance
	 */
	public function test_select_groups_multi_selection(){

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

		$this->assertEquals($result, $expected, "Form::select (optgroups) failed to create a proper selection ... variation 3");

	}

}

