<?php

FUnit::test("Hollow::get() returns null on an unset key", function(){

	$value = \Chevron\Hollow\Hollow::get("TestKey");

	FUnit::equal($value, null);
});

FUnit::test("Hollow::set() returns the value being set", function(){

	$scalar = "TestValue";
	$value = \Chevron\Hollow\Hollow::set("TestKey", $scalar);

	FUnit::equal($value, $scalar);
});

FUnit::test("Hollow::get() return values", function(){

	$scalar = "TestValue";
	\Chevron\Hollow\Hollow::set("TestKey", $scalar);
	$value = \Chevron\Hollow\Hollow::get("TestKey");

	FUnit::equal($value, $scalar);
});

FUnit::test("Hollow::set() stores an instance of \Closure", function(){

	$closure = \Chevron\Hollow\Hollow::set("TestKey", function(){ return "FunctionReturn"; });

	if($closure InstanceOf \Closure){
		FUnit::ok(1);
	}else{
		FUnit::fail("failed to store a \Closure");
	}
});

FUnit::test("Hollow::get() returns the correct value", function(){

	$expected_data = "FunctionReturn";
	\Chevron\Hollow\Hollow::set("TestKey", function()use($expected_data){ return $expected_data; });
	$value = \Chevron\Hollow\Hollow::get("TestKey");

	FUnit::equal($value, $expected_data);
});

FUnit::test("Hollow::get() return values of set closures", function(){

	\Chevron\Hollow\Hollow::set("TestXKey", function(){ return uniqid(); });

	$value1 = \Chevron\Hollow\Hollow::get("TestXKey");
	$value2 = \Chevron\Hollow\Hollow::get("TestXKey");
	$value3 = \Chevron\Hollow\Hollow::get("TestXKey", true);
	$value4 = \Chevron\Hollow\Hollow::get("TestXKey", true);
	$value5 = \Chevron\Hollow\Hollow::get("TestXKey");

	FUnit::equal($value1, $value2, "same value twice when callable");
	FUnit::not_equal($value1, $value3, "different value when asked for a new value");
	FUnit::not_equal($value3, $value4, "different value when asked for a new value");
	FUnit::equal($value1, $value5, "initial value after a new value was also created");
});



