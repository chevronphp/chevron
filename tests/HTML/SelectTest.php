<?php

use \Chevron\HTML;

FUnit::test("HTML\Select::__callStatic() w/o optgroups, attributes", function(){
	$el = HTML\Select::testSelect(
		["one" => "1", "two" => "2"],
		["one"],
		["class" => ["test", "testtwo"]]
	);

	$expected = "<select class=\"test testtwo\" name=\"testSelect\"><option value=\"one\" selected=\"selected\">1</option><option value=\"two\">2</option></select>";

	Funit::equal($expected, $el->render());
});

FUnit::test("HTML\Select::__callStatic() w/ optgroups w/o attributes", function(){
	$el = HTML\Select::testSelect(
		["numbers" => ["one" => "1", "two" => "2"]],
		["one"]
	);

	$expected = "<select name=\"testSelect\"><optgroup label=\"numbers\"><option value=\"one\" selected=\"selected\">1</option><option value=\"two\">2</option></optgroup></select>";

	Funit::equal($expected, $el->render());
});

FUnit::test("HTML\Select::__callStatic() w/ optgroups w/ attributes", function(){
	$el = HTML\Select::testSelect(
		["numbers" => ["one" => "1", "two" => "2"]],
		["one"],
		["class" => ["one", "two"]]
	);

	$expected = "<select class=\"one two\" name=\"testSelect\"><optgroup label=\"numbers\"><option value=\"one\" selected=\"selected\">1</option><option value=\"two\">2</option></optgroup></select>";

	Funit::equal($expected, $el->render());
});

