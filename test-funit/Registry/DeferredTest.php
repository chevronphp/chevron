<?php

FUnit::test("Test Deferred::invoke", function(){

	$B = new \Chevron\Registry\Deferred();

	$B->register("one", function($payload){
		return $payload[0] + $payload[1];
	}, array(3, 4));

	$rst = $B->invoke("one");

	FUnit::equal(7, $rst, "Deferred::invoke with payload");

});

FUnit::test("Test Deferred::invokeArgs", function(){

	$B = new \Chevron\Registry\Deferred();

	$B->register("one", function($payload){
		return $payload[0] + $payload[1];
	}, array(3, 4));

	$rst = $B->invokeArgs("one", array(6, 7));

	FUnit::equal(13, $rst, "Deferred::invoke with args {$rst}");

});

FUnit::test("Test Deferred::___call", function(){

	$B = new \Chevron\Registry\Deferred();

	$B->register("one", function($payload){
		return $payload[0] + $payload[1];
	}, array(3, 4));

	$rst = $B->one(array(4, 5));

	FUnit::equal(9, $rst, "Deferred::__call with args");

});