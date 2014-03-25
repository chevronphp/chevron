<?php

class ContainerDeferredTest extends PHPUnit_Framework_TestCase {

	public function getLoadedRegistry() {
		$registry = new \Chevron\Container\Deferred;

		$registry->setMany(array("func" => function($val){
			return md5($val);
		}));

		return $registry;
	}

	public function testInvoke(){
		$registry = $this->getLoadedRegistry();

		$one = $registry->invoke("func", array("one"));
		$two = $registry->invoke("func", array("two"));

		$this->assertNotEquals($one, $two, "Deferred::invoke() failed to call twice");

		$one = $registry->func("one");
		$two = $registry->func("two");

		$this->assertNotEquals($one, $two, "Deferred::__call() failed to call twice");
	}

	public function testOnce(){
		$registry = $this->getLoadedRegistry();

		$one = $registry->once("func", array("one"));
		$two = $registry->once("func", array("two"));

		$this->assertEquals($one, $two, "Deferred::once() failed to call once");

	}

}

