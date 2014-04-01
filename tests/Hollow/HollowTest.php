<?php

require_once("tests/bootstrap.php");

use Chevron\Hollow\Hollow;

FUnit::test("Hollow::get() returns null on an unset key", function(){

	$value = Hollow::get("TestKey");

	FUnit::equal($value, null);
});

FUnit::test("Hollow::set() returns the value being set", function(){

	$scalar = "TestValue";
	$value = Hollow::set("TestKey", $scalar);

	FUnit::equal($value, $scalar);
});

FUnit::test("Hollow::get() return values", function(){

	$scalar = "TestValue";
	Hollow::set("TestKey", $scalar);
	$value = Hollow::get("TestKey");

	FUnit::equal($value, $scalar);
});

FUnit::test("Hollow::set() stores an instance of \Closure", function(){

	$closure = Hollow::set("TestKey", function(){ return "FunctionReturn"; });

	if($closure InstanceOf \Closure){
		FUnit::ok(1);
	}else{
		FUnit::fail("failed to store a \Closure");
	}
});

FUnit::test("Hollow::get() returns the correct value", function(){

	$expected_data = "FunctionReturn";
	Hollow::set("TestKey", function()use($expected_data){ return $expected_data; });
	$value = Hollow::get("TestKey");

	FUnit::equal($value, $expected_data);
});

FUnit::test("Hollow::get() return values of set closures", function(){

	Hollow::set("TestXKey", function(){ return uniqid(); });

	$value1 = Hollow::get("TestXKey");
	$value2 = Hollow::get("TestXKey");
	$value3 = Hollow::get("TestXKey", true);
	$value4 = Hollow::get("TestXKey", true);
	$value5 = Hollow::get("TestXKey");

	FUnit::equal($value1, $value2, "same value twice when callable");
	FUnit::not_equal($value1, $value3, "different value when asked for a new value");
	FUnit::not_equal($value3, $value4, "different value when asked for a new value");
	FUnit::equal($value1, $value5, "initial value after a new value was also created");
});



