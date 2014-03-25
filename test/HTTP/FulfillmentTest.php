<?php

class FulfillmentTest extends PHPUnit_Framework_TestCase {

	public function test_setLayout(){

		$F = new \Chevron\HTTP\Utils\Fulfillment;

		$F->setLayout("funkatron");

		$value = $F->layout;

		$this->assertEquals("funkatron", $value, "Fulfillment::setLayout failed.");

	}

	public function test_setError(){

		$F = new \Chevron\HTTP\Utils\Fulfillment;

		$F->setError(function(){ echo "BOOM"; });

		$value = $F->error;

		$this->assertEquals(true, is_callable($value), "Fulfillment::setError failed.");

	}

	public function testSetStatusCode(){

		$F = new \Chevron\HTTP\Utils\Fulfillment;

		$F->setContentType("xml");

		$value = $F->headers;
		$expected = array(101 => "Content-Type: text/xml");

		$this->assertEquals($expected, $value, "Fulfillment::setContentType failed.");

	}

	public function testSetContentType(){

		$F = new \Chevron\HTTP\Utils\Fulfillment;

		$F->setStatusCode(302);

		$value = $F->headers;
		$expected = array(102 => "HTTP/1.1 302 Temporary Redirect");

		$this->assertEquals($expected, $value, "Fulfillment::setStatusCode failed.");

	}

}