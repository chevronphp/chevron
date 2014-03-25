<?php

FUnit::test("Fulfillment::setLayout()", function(){

	$F = new \Chevron\HTTP\Utils\Fulfillment;

	$F->setLayout("funkatron");

	$value = $F->layout;

	FUnit::equal("funkatron", $value);

});

FUnit::test("Fulfillment::setError()", function(){

	$F = new \Chevron\HTTP\Utils\Fulfillment;

	$F->setError(function(){ echo "BOOM"; });

	$value = $F->error;

	FUnit::equal(true, is_callable($value));

});

FUnit::test("Fulfillment::setContentType()", function(){

	$F = new \Chevron\HTTP\Utils\Fulfillment;

	$F->setContentType("xml");

	$value = $F->headers;
	$expected = array(101 => "Content-Type: text/xml");

	FUnit::equal($expected, $value);

});

FUnit::test("Fulfillment::setStatusCode()", function(){

	$F = new \Chevron\HTTP\Utils\Fulfillment;

	$F->setStatusCode(302);

	$value = $F->headers;
	$expected = array(102 => "HTTP/1.1 302 Temporary Redirect");

	FUnit::equal($expected, $value);

});


