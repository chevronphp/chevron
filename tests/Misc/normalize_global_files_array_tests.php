<?php

require_once("tests/bootstrap.php");

require_once("src/Chevron/Misc/normalize_global_files_array.php");

FUnit::test("normalize_global_files_array() w/ a single field & single value", function(){

	$input["fieldname"] = array(
		'name'     => "field",
		'type'     => "plain/text",
		'size'     => "1024",
		'tmp_name' => "asdfqwerty",
		'error'    => "0",
	);

	$output = normalize_global_files_array($input);

	$expected = array(
		"fieldname" => array(
			array(
				'name'     => "field",
				'type'     => "plain/text",
				'size'     => "1024",
				'tmp_name' => "asdfqwerty",
				'error'    => "0",
			)
		)
	);

	FUnit::equal($expected, $output);

});

FUnit::test("normalize_global_files_array() w/ a single field & multiple values", function(){

	$input["fieldname"] = array(
		'name'     => array("field1", "field2"),
		'type'     => array("plain/text", "plain/text"),
		'size'     => array("1024", "1024"),
		'tmp_name' => array("asdfqwerty1", "asdfqwerty2"),
		'error'    => array("0", "0"),
	);

	$output = normalize_global_files_array($input);

	$expected = array(
		"fieldname" => array(
			array(
				'name'     => "field1",
				'type'     => "plain/text",
				'size'     => "1024",
				'tmp_name' => "asdfqwerty1",
				'error'    => "0",
			),
			array(
				'name'     => "field2",
				'type'     => "plain/text",
				'size'     => "1024",
				'tmp_name' => "asdfqwerty2",
				'error'    => "0",
			)
		)
	);

	FUnit::equal($expected, $output);

});

FUnit::test("normalize_global_files_array() w/ a multiple fields & mixed values", function(){

	$input = array(
		"fieldname1" => array(
			'name'     => "field",
			'type'     => "plain/text",
			'size'     => "1024",
			'tmp_name' => "asdfqwerty",
			'error'    => "0",
		),
		"fieldname2" => array(
			'name'     => array("field1", "field2"),
			'type'     => array("plain/text", "plain/text"),
			'size'     => array("1024", "1024"),
			'tmp_name' => array("asdfqwerty1", "asdfqwerty2"),
			'error'    => array("0", "0"),
		)
	);

	$output = normalize_global_files_array($input);

	$expected = array(
		"fieldname1" => array(
			array(
				'name'     => "field",
				'type'     => "plain/text",
				'size'     => "1024",
				'tmp_name' => "asdfqwerty",
				'error'    => "0",
			)
		),
		"fieldname2" => array(
			array(
				'name'     => "field1",
				'type'     => "plain/text",
				'size'     => "1024",
				'tmp_name' => "asdfqwerty1",
				'error'    => "0",
			),
			array(
				'name'     => "field2",
				'type'     => "plain/text",
				'size'     => "1024",
				'tmp_name' => "asdfqwerty2",
				'error'    => "0",
			)
		)
	);

	FUnit::equal($expected, $output);

});
