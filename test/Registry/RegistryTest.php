<?php

class RegistryTest extends PHPUnit_Framework_TestCase {

	public function testLoadData(){

		$registry   = new \Chevron\Registry\Registry;
		$reflection = new ReflectionClass($registry);

		$data = array(
			"numeric" => 23,
			"string"  => "Led Zeppelin",
			"array"   => array(
				"numeric" => 46,
				"string"  => "The Beatles",
			)
		);

		$registry->load_data($data);

		$map = $reflection->getProperty("map");
		$map->setAccessible(true);

		$value = $map->getValue($registry);

		$this->assertInternalType("array", $value, "Registry::load_data (type) failed");
		$this->assertEquals(count($value), 3, "Registry::load_data (count) failed");
		$this->assertInternalType("int", $value["numeric"], "Registry::load_data (int) failed");
		$this->assertInternalType("string", $value["string"], "Registry::load_data (string) failed");
		$this->assertInternalType("array", $value["array"], "Registry::load_data (array) failed");
		$this->assertEquals(count($value["array"]), 2, "Registry::load_data (sub-array) count failed");
		$this->assertInternalType("int", count($value["array"]["numeric"]), "Registry::load_data (sub-array) type failed");
		$this->assertInternalType("string", $value["array"]["string"], "Registry::load_data (sub-array) type failed");

	}

	public function getLoadedRegistry(){
		$registry   = new \Chevron\Registry\Registry;

		$data = array(
			"numeric" => 23,
			"string"  => "Led Zeppelin",
			"array"   => array(
				"numeric" => 46,
				"string"  => "The Beatles",
			)
		);

		$registry->load_data($data);
		return $registry;
	}

	public function testMagicGetInt(){

		$registry = $this->getLoadedRegistry();

		$data = $registry->numeric;
		$expected = 23;

		// get int
		$this->assertEquals($data, $expected, "Registry::__get (int) failed to return correct data");
		$this->assertInternalType("int", $data, "Registry::__get (int) failed to return correct type");

	}

	public function testMagicGetString(){

		$registry = $this->getLoadedRegistry();

		$data = $registry->string;
		$expected = "Led Zeppelin";

		// get string
		$this->assertEquals($data, $expected, "Registry::__get (string) failed to return correct data");
		$this->assertInternalType("string", $data, "Registry::__get (string) failed to return correct type");

	}

	/**
	 * @depends testMagicGetString
	 */
	public function testMagicGetSuccessStatus(){

		$registry = $this->getLoadedRegistry();

		$data = $registry->string;

		$data = $registry->success();
		$expected = true;

		// test success
		$this->assertEquals($data, $expected, "Registry::success (successful) failed to return correct data");
		$this->assertInternalType("bool", $data, "Registry::success (successful) failed to return correct type");

	}

	public function testMagicGetArray(){

		$registry = $this->getLoadedRegistry();

		$data = $registry->array;
		$expected = array(
			"numeric" => 46,
			"string"  => "The Beatles",
		);

		// get array
		$this->assertEquals($data, $expected, "Registry::__get (array) failed to return correct data");
		$this->assertInternalType("array", $data, "Registry::__get (array) failed to return correct type");

	}

	public function testMagicGetNothing(){

		$registry = $this->getLoadedRegistry();

		$data = $registry->not_string;
		$expected = null;

		// get value that doesn't exist
		$this->assertEquals($data, $expected, "Registry::__get (non set value) failed to return correct data");
		$this->assertInternalType("null", $data, "Registry::__get (non set value) failed to return correct type");

	}

	/**
	 * @depends testMagicGetNothing
	 */
	public function testMagicGetNotSuccessStatus(){

		$registry = $this->getLoadedRegistry();

		$data = $registry->not_string;

		$data = $registry->success();
		$expected = false;

		// test unsuccess
		$this->assertEquals($data, $expected, "Registry::success (failure) failed to return correct data");
		$this->assertInternalType("bool", $data, "Registry::success (failure) failed to return correct type");
	}

	public function testMagicSetInt(){

		$registry = $this->getLoadedRegistry();

		$registry->numeric = 92;
		$data = $registry->numeric;

		// set int
		$this->assertNotEquals($data, 23, "Registry::__set (int) failed to return correct data");
		$this->assertInternalType("int", $data, "Registry::__set (int) failed to return correct type");

	}

	/**
	 * @depends testMagicSetInt
	 */
	public function testMagicSetSuccessStatus(){

		$registry = $this->getLoadedRegistry();

		$registry->numeric = 92;

		$data = $registry->success();
		$expected = true;

		// test success
		$this->assertEquals($data, $expected, "Registry::success (successful) failed to return correct data");
		$this->assertInternalType("bool", $data, "Registry::success (successful) failed to return correct type");

	}

	public function testMagicSetChangeValueAndType(){

		$registry = $this->getLoadedRegistry();

		$registry->string = 46;
		$data = $registry->string;

		// change value and type
		$this->assertNotEquals($data, "Led Zeppelin", "Registry::__set (change value and type) failed to return correct data");
		$this->assertInternalType("int", $data, "Registry::__set (change value and type) failed to return correct type");

	}

	public function testMagicSetNothing(){

		$registry = $this->getLoadedRegistry();

		$registry->not_string = "not a string";
		$data = $registry->not_string;

		// set value not there
		$this->assertEquals($data, null, "Registry::__set (non set value) failed to return correct data");
		$this->assertInternalType("null", $data, "Registry::__set (non set value) failed to return correct type");

	}

	/**
	 * @depends testMagicSetNothing
	 */
	public function testMagicSetNothingSuccessStatus(){

		$registry = $this->getLoadedRegistry();

		$registry->not_string = "not a string";

		$data = $registry->success();
		$expected = false;

		// test unsuccess
		$this->assertEquals($data, $expected, "Registry::success (failure) failed to return correct data");
		$this->assertInternalType("bool", $data, "Registry::success (failure) failed to return correct type");

	}

}