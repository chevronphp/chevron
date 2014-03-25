<?php

FUnit::test("array_flatten", function(){
	Chevron\Misc\Loader::loadFunctions("array_flatten");

	$input = array(
		"one"   => "two",
		"three" => array(
			"four",
			"five" => "six"
		),
		"seven",
	);

	$output = array_flatten($input);

	// values only
	$expected = array(
		"two",
		"four",
		"six",
		"seven",
	);

	FUnit::equal($output, $expected);

});

FUnit::test("array_flatten_unique", function(){
	Chevron\Misc\Loader::loadFunctions("array_flatten_unique");

	$input = array(
		"one"   => "two",
		"three" => array(
			"two",
			"five" => "two"
		),
		"two",
	);

	$output = array_flatten_unique($input);

	// values only
	$expected = array(
		"two",
		"two",
		"two",
	);

	// print_r($output);
	// exit();

	FUnit::equal($output, $expected);

});

FUnit::test("array_kmerge", function(){
	Chevron\Misc\Loader::loadFunctions("array_kmerge");

	$one = array(
		"one" => "two",
		"three",
		"four" => "five",
		"six"
	);

	$two = array(
		"one" => "goose",
		"three",
		"four" => "goose",
		"goose",
	);

	$expected = array(
		"one" => "goose",
		"three",
		"four" => "goose",
		"goose",
	);

	$three = array_kmerge($one, $two);

	FUnit::equal($three, $expected);

	$expected = array(
		"one" => "two",
		"three",
		"four" => "five",
		"six",
	);

	$three = array_kmerge($two, $one);

	FUnit::equal($three, $expected);

});