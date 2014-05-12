<?php

require_once("tests/bootstrap.php");

use \Chevron\HTML;

FUnit::test("HTML\ElementDispatcher::__call()", function(){

	$dispatcher = new HTML\ElementDispatcher;

	$el = $dispatcher->div("This is a <bad>data string</bad>");
	Funit::equal("<div>This is a &lt;bad&gt;data string&lt;/bad&gt;</div>", $el->render());
});

FUnit::test("HTML\FormDispatcher::__call()", function(){

	$dispatcher = new HTML\FormDispatcher;

	$el = $dispatcher->text("name", "value");
	Funit::equal("<input name=\"name\" type=\"text\" value=\"value\" />", $el->render());
});

FUnit::test("HTML\SelectDispatcher::__call()", function(){

	$dispatcher = new HTML\SelectDispatcher;

	$el = $dispatcher->testSelect(
		["one" => "1", "two" => "2"],
		["one"],
		["class" => ["test", "testtwo"]]
	);

	$expected = "<select class=\"test testtwo\" name=\"testSelect\"><option value=\"one\" selected=\"selected\">1</option><option value=\"two\">2</option></select>";

	Funit::equal($expected, $el->render());
});

