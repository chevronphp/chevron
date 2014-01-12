<?php

class DeferredTest extends PHPUnit_Framework_TestCase {

	public function testInvoke(){

		$B = new \Chevron\Registry\Deferred();

		$B->register("one", function($payload){
			return $payload[0] + $payload[1];
		}, array(3, 4));

		$rst = $B->invoke("one");

		$this->assertEquals(7, $rst, "DeferredRegistry::invoke with payload failed");

	}

	public function testInvokeArgs(){

		$B = new \Chevron\Registry\Deferred();

		$B->register("one", function($payload){
			return $payload[0] + $payload[1];
		}, array(3, 4));

		$rst = $B->invokeArgs("one", array(6, 7));

		$this->assertEquals(13, $rst, "DeferredRegistry::invoke with args failed");

	}

	public function testInvoke__call(){

		$B = new \Chevron\Registry\Deferred();

		$B->register("one", function($payload){
			return $payload[0] + $payload[1];
		}, array(3, 4));

		$rst = $B->one(array(4, 5));

		$this->assertEquals(9, $rst, "DeferredRegistry::__call with args failed");

	}
}