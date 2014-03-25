<?php

class ContainerRegistryTest extends PHPUnit_Framework_TestCase {

	public function getLoadedRegistry() {
		$registry = new \Chevron\Container\Registry;

		$data = array(
			"numeric" => 23,
			"string"  => "Led Zeppelin",
			"array"   => array(
				"numeric" => 46,
				"string"  => "The Beatles",
			)
		);

		$registry->setMany($data);

		return $registry;
	}

	public function testLength(){
		$registry = $this->getLoadedRegistry();

		$len = $registry->length();

		$this->assertEquals(3, $len, "Registry::length() failed");
	}

	public function testGet(){
		$registry = $this->getLoadedRegistry();

		$this->assertEquals(23, $registry->get("numeric"), "Registry::setMany() failed (value)");
		$this->assertInternalType("int", $registry->get("numeric"), "Registry::setMany() failed (type)");

		$this->assertEquals("Led Zeppelin", $registry->get("string"), "Registry::setMany() failed (value)");
		$this->assertInternalType("string", $registry->get("string"), "Registry::setMany() failed (type)");

		$expected = array(
			"numeric" => 46,
			"string"  => "The Beatles",
		);

		$this->assertEquals($expected, $registry->get("array"), "Registry::setMany() failed (value)");
		$this->assertInternalType("array", $registry->get("array"), "Registry::setMany() failed (type)");

	}

	public function testHas(){
		$registry = $this->getLoadedRegistry();

		$this->assertEquals(true, $registry->has("array"), "Registry::has() failed");
		$this->assertEquals(false, $registry->has("empty"), "Registry::has() failed");

	}

	public function testGetIterator(){
		$registry = $this->getLoadedRegistry();

		$iter = $registry->getIterator();

		$data = array(
			"numeric" => 23,
			"string"  => "Led Zeppelin",
			"array"   => array(
				"numeric" => 46,
				"string"  => "The Beatles",
			)
		);

		$this->assertInstanceOf("ArrayIterator", $iter, "Registry::getIterator() failed (type)");

		foreach($iter as $key => $value){
			$this->assertEquals(true, array_key_exists($key, $data), "Registry::getIterator() key does not exist");
		}

	}

}