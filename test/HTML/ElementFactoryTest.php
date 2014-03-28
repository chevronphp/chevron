<?php

class ElementFactoryTest extends PHPUnit_Framework_TestCase {

	public function test__call1(){
		$elF = new \Chevron\HTML\ElementFactory;

		$el = $elF->p("This is html.");

		$this->assertEquals("<p>This is html.</p>", (string)$el, "ElementFactory::__call() failed #1");

	}

	public function test__call2(){
		$elF = new \Chevron\HTML\ElementFactory;

		$el = $elF->p("This is html.");

		$el->setInnerHTML("This is <new>html</new>.");

		$el->setAttributes(array(
			"class" => "testClass",
		));

		$this->assertEquals("<p class=\"testClass\">This is &lt;new&gt;html&lt;/new&gt;.</p>", (string)$el, "ElementFactory::__call() failed #2");

	}

	public function test__call3(){
		$elF = new \Chevron\HTML\ElementFactory;

		$el = $elF->text("textName", "a <bad>textValue</bad>");

		$el->setAttributes(array(
			"class" => "testClass",
		));

		$expected = "<input type=\"text\" name=\"textName\" value=\"a &lt;bad&gt;textValue&lt;/bad&gt;\" class=\"testClass\" />";

		$this->assertEquals($expected, (string)$el, "ElementFactory::__call() failed #3");

	}

	public function test__call4(){
		$elF = new \Chevron\HTML\ElementFactory;

		$el = $elF->radio("textName", "a <bad>textValue</bad>", true);

		$expected = "<input type=\"radio\" name=\"textName\" value=\"a &lt;bad&gt;textValue&lt;/bad&gt;\" checked=\"checked\" />";

		$this->assertEquals($expected, (string)$el, "ElementFactory::__call() failed #4");

	}

	public function test_select_all_params(){

		$elF = new \Chevron\HTML\ElementFactory;

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

		$this->assertEquals($result, $expected, "ElementFactory::select() failed to create a proper selection ... variation 1");

	}

	public function test_select_no_selection(){

		$elF = new \Chevron\HTML\ElementFactory;

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

		$this->assertEquals($result, $expected, "ElementFactory::select() failed to create a proper selection ... variation 2");

	}

	public function test_select_multi_selection(){

		$elF = new \Chevron\HTML\ElementFactory;

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

		$this->assertEquals($result, $expected, "ElementFactory::select() failed to create a proper selection ... variation 3");

	}

	public function test_select_no_selection_no_attributes(){

		$elF = new \Chevron\HTML\ElementFactory;

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

		$this->assertEquals($result, $expected, "ElementFactory::select() failed to create a proper selection ... variation 4");

	}

	public function test_select_groups_all_params(){

		$elF = new \Chevron\HTML\ElementFactory;

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

		$this->assertEquals($result, $expected, "ElementFactory::select() (optgroups) failed to create a proper selection ... variation 1");

	}

	public function test_select_groups_no_selection(){

		$elF = new \Chevron\HTML\ElementFactory;

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

		$this->assertEquals($result, $expected, "ElementFactory::select() (optgroups) failed to create a proper selection ... variation 2");

	}

	public function test_select_groups_multi_selection(){

		$elF = new \Chevron\HTML\ElementFactory;

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

		$this->assertEquals($result, $expected, "ElementFactory::select() (optgroups) failed to create a proper selection ... variation 3");

	}

}