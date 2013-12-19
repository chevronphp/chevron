<?php

class HollowTest extends PHPUnit_Framework_TestCase {

	public function testEmtpyMap(){

		$hollow = new ReflectionClass("Chevron\Hollow\Hollow");
		$map = $hollow->getProperty("map");
		$map->setAccessible(true);

		$expected_data = array();
		$value = $map->getValue($hollow);

		$this->assertInternalType("array", $value,  "Type Error: Hollow::$map is not an array");
		$this->assertEquals($value, $expected_data, "Initialize Error: Hollow::$map is not empty");
	}

	public function testGetDefault(){

		$hollow = new ReflectionClass("Chevron\Hollow\Hollow");
		$get = $hollow->getMethod("get");

		$expected_data = null;
		$value = $get->invoke($hollow, "TestKey");

		$this->assertEquals($value, $expected_data, "Getter Error: Hollow::get should return null on an unset key");
	}

	public function testSet(){

		$hollow = new ReflectionClass("Chevron\Hollow\Hollow");
		$set = $hollow->getMethod("set");

		$expected_data = "TestValue";
		$value = $set->invoke($hollow, "TestKey", "TestValue");

		$this->assertEquals($value, $expected_data, "Setter Error: Hollow::set should return the value being set");
	}

	/**
	 * @depends testSet
	 */
	public function testGet(){

		$hollow = new ReflectionClass("Chevron\Hollow\Hollow");
		$get = $hollow->getMethod("get");
		$set = $hollow->getMethod("set");

		$value = $set->invoke($hollow, "TestKey", "TestValue");

		$expected_data = "TestValue";
		$value = $get->invoke($hollow, "TestKey");

		$this->assertEquals($value, $expected_data, "Getter Error: Hollow::get didn't return the correct value");
	}

	/**
	 * @depends testSet
	 */
	public function testSetCallable(){

		$hollow = new ReflectionClass("Chevron\Hollow\Hollow");
		$set = $hollow->getMethod("set");
		$get = $hollow->getMethod("get");

		$expected_data = "TestValue";
		$value = $set->invoke($hollow, "TestKey", function(){ return "FunctionReturn"; });

		$this->assertInstanceOf("Closure", $value, "Setter Error: Hollow:set setting closure didn't return Closure");
	}

	/**
	 * @depends testSetCallable
	 */
	public function testGetCallable(){

		$hollow = new ReflectionClass("Chevron\Hollow\Hollow");
		$set = $hollow->getMethod("set");
		$get = $hollow->getMethod("get");

		$value = $set->invoke($hollow, "TestKey", function(){ return "FunctionReturn"; });

		$expected_data = "FunctionReturn";
		$value = $get->invoke($hollow, "TestKey");

		$this->assertEquals($value, $expected_data, "Getter Error: Hollow::get closure didn't return the correct value");
	}

	/**
	 * @depends testGetCallable
	 */
	public function testGetCallableNew() {

		$hollow = new ReflectionClass("Chevron\Hollow\Hollow");
		$set    = $hollow->getMethod("set");
		$get    = $hollow->getMethod("get");

		$set->invoke($hollow, "TestXKey", function () { return uniqid(); });

		$value1 = $get->invoke($hollow, "TestXKey");
		$value2 = $get->invoke($hollow, "TestXKey");
		$value3 = $get->invoke($hollow, "TestXKey", true);
		$value4 = $get->invoke($hollow, "TestXKey", true);
		$value5 = $get->invoke($hollow, "TestXKey");

		$this->assertEquals($value1, $value2, "Getter Error: Hollow::get closure didn't return the same value twice when callable");
		$this->assertNotEquals($value1, $value3, "Getter Error: Hollow::get returned the same value twice despite asking for a new value");
		$this->assertNotEquals($value3, $value4, "Getter Error: Hollow::get returned the same value twice despite asking for a new value");
		$this->assertEquals($value1, $value5, "Getter Error: Hollow::get closure didn't return the initial value after a new value was also created");
	}


	/**
	 * @depends testSetCallable
	 */
	public function testGetSingleton(){

		$hollow = new ReflectionClass("Chevron\Hollow\Hollow");
		$set = $hollow->getMethod("set");
		$get = $hollow->getMethod("get");

		$set->invoke($hollow, "TestKey", function(){

			$inst = md5(mt_rand(1, 9999));
			return $inst;
		});

		$first_call  = $get->invoke($hollow, "TestKey");
		$second_call = $get->invoke($hollow, "TestKey");

		$this->assertEquals($first_call, $second_call, "Getter Error: Hollow::get (singleton) closure didn't return the same value");

	}

	/**
	 * @depends testSetCallable
	 */
	public function testDuplicate(){

		$hollow = new ReflectionClass("Chevron\Hollow\Hollow");
		$map = $hollow->getProperty("map");
		$map->setAccessible(true);

		$set = $hollow->getMethod("set");
		$duplicate = $hollow->getMethod("duplicate");

		$set->invoke($hollow, "TestKey", function(){

			$inst = md5(mt_rand(1, 9999));
			return $inst;
		});

		$duplicate->invoke($hollow, "TestKey", "TestKey2");
		$first_call  = $duplicate->invoke($hollow, "TestKey", "TestKey2");
		$vals = $map->getValue();

		$this->assertSame($vals["TestKey"], $vals["TestKey2"], "Getter Error: Hollow::duplicate value didn't copy");
	}

}
