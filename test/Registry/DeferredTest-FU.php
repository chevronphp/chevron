<?php

Funit::test("Test DeferredRegistry::invoke", function(){

	$B = new DeferredRegistry();

	$B->register("one", function($payload){
		return $payload[0] + $payload[1];
	}, array(3, 4));

	$rst = $B->invoke("one");

	FUnit::equal(7, $rst, "DeferredRegistry::invoke with payload");

});

Funit::test("Test DeferredRegistry::invokeArgs", function(){

	$B = new DeferredRegistry();

	$B->register("one", function($payload){
		return $payload[0] + $payload[1];
	}, array(3, 4));

	$rst = $B->invokeArgs("one", array(6, 7));

	FUnit::equal(13, $rst, "DeferredRegistry::invoke with args {$rst}");

});

Funit::test("Test DeferredRegistry::___call", function(){

	$B = new DeferredRegistry();

	$B->register("one", function($payload){
		return $payload[0] + $payload[1];
	}, array(3, 4));

	$rst = $B->one(array(4, 5));

	FUnit::equal(9, $rst, "DeferredRegistry::__call with args");

});