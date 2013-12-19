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

		$registry->data($data);

		$map = $reflection->getProperty("map");
		$map->setAccessible(true);

		$value = $map->getValue($registry);

		$this->assertInternalType("array", $value, "Registry::data (type) failed");
		$this->assertEquals(count($value), 3, "Registry::data (count) failed");
		$this->assertInternalType("int", $value["numeric"], "Registry::data (int) failed");
		$this->assertInternalType("string", $value["string"], "Registry::data (string) failed");
		$this->assertInternalType("array", $value["array"], "Registry::data (array) failed");
		$this->assertEquals(count($value["array"]), 2, "Registry::data (sub-array) count failed");
		$this->assertInternalType("int", count($value["array"]["numeric"]), "Registry::data (sub-array) type failed");
		$this->assertInternalType("string", $value["array"]["string"], "Registry::data (sub-array) type failed");

	}

	public function testMagicGetInt() {

		$registry = $this->getLoadedRegistry();

		$data     = $registry->numeric;
		$expected = 23;

		// get int
		$this->assertEquals($data, $expected, "Registry::__get (int) failed to return correct data");
		$this->assertInternalType("int", $data, "Registry::__get (int) failed to return correct type");

	}

	public function getLoadedRegistry() {
		$registry = new \Chevron\Registry\Registry;

		$data = array(
			"numeric" => 23,
			"string"  => "Led Zeppelin",
			"array"   => array(
				"numeric" => 46,
				"string"  => "The Beatles",
			)
		);

		$registry->data($data);

		return $registry;
	}

	public function testMagicGetString() {

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
		$this->assertEquals($expected, $data, "Registry::__get (non set value) failed to return correct data");
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
		$this->assertEquals($expected, $data, "Registry::success (failure) failed to return correct data");
		$this->assertInternalType("bool", $data, "Registry::success (failure) failed to return correct type");
	}

	public function testMagicSetInt(){

		$registry = $this->getLoadedRegistry();

		$registry->numeric = 92;
		$data = $registry->numeric;

		// set int
		$this->assertNotEquals(23, $data, "Registry::__set (int) failed to return correct data");
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
		$this->assertEquals($expected, $data, "Registry::success (successful) failed to return correct data");
		$this->assertInternalType("bool", $data, "Registry::success (successful) failed to return correct type");

	}

	public function testMagicSetChangeValueAndType(){

		$registry = $this->getLoadedRegistry();

		$registry->string = 46;
		$data = $registry->string;

		// change value and type
		$this->assertNotEquals("Led Zeppelin", $data, "Registry::__set (change value and type) failed to return correct data");
		$this->assertInternalType("int", $data, "Registry::__set (change value and type) failed to return correct type");

	}

	public function testMagicSetNewValue(){

		$registry = $this->getLoadedRegistry();

		$registry->not_string = "not a string";
		$data = $registry->not_string;

		// set value not there
		$this->assertEquals("not a string", $data, "Registry::__set (non set value) failed to return correct data");
		$this->assertInternalType("string", $data, "Registry::__set (non set value) failed to return correct type");

	}

	/**
	 * @depends testMagicSetNewValue
	 */
	public function testMagicSetNewValueSuccessStatus(){

		$registry = $this->getLoadedRegistry();

		$registry->not_string = "not a string";

		$data = $registry->success();
		$expected = true;

		// test unsuccess
		$this->assertEquals($expected, $data, "Registry::success (failure) failed to return correct data");
		$this->assertInternalType("bool", $data, "Registry::success (failure) failed to return correct type");

	}

	public function testMagicIsset() {

		$registry = $this->getLoadedRegistry();

		$this->assertSame(true, isset($registry->numeric), "Registry::isset truth-y failure");
		$this->assertSame(false, isset($registry->madeupcrap), "Registry::isset false-y failure");

	}

	/**
	 * @deprecated
	 */
	public function _MagicSetNothing(){

		$registry = $this->getLoadedRegistry();

		$registry->not_string = "not a string";
		$data = $registry->not_string;

		// set value not there
		$this->assertEquals(null, $data, "Registry::__set (non set value) failed to return correct data");
		$this->assertInternalType("null", $data, "Registry::__set (non set value) failed to return correct type");

	}

	/**
	 * @deprecated
	 * @depends testMagicSetNothing
	 */
	public function _MagicSetNothingSuccessStatus(){

		$registry = $this->getLoadedRegistry();

		$registry->not_string = "not a string";

		$data = $registry->success();
		$expected = false;

		// test unsuccess
		$this->assertEquals($expected, $data, "Registry::success (failure) failed to return correct data");
		$this->assertInternalType("bool", $data, "Registry::success (failure) failed to return correct type");

	}

}
