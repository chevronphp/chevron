<?php

class FulfillmentTest extends PHPUnit_Framework_TestCase {

	public function test_setHeader(){

		$F = new \Chevron\HTTP\Utils\Fulfillment;

		$headers = array("content-type" => "This is a content type");

		$F->setHeader("content-type", "This is a content type");

		$value = $F->headers;

		$this->assertEquals($headers, $value, "Fulfillment::setHeader failed.");

	}

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

}