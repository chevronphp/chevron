<?php

class CacheTest extends PHPUnit_Framework_TestCase {

	public function testMockInterface(){

		$mock = new \Chevron\Cache\Drivers\Mock();

		$this->assertInstanceOf("\Chevron\Cache\Drivers\DriverInterface", $mock, "Mock does not implement the correct interface");

	}

	public function testGet(){

		$mock = new \Chevron\Cache\Drivers\Mock();
		$cache = new Chevron\Cache\Cache($mock, 300);

		$this->assertEquals(null, $cache->get("missing"), "Cache::get() failed");

	}

	public function testSet(){

		$mock = new \Chevron\Cache\Drivers\Mock();
		$cache = new Chevron\Cache\Cache($mock, 300);

		$this->assertEquals(true, $cache->set("missing", "value"), "Cache::set() failed");

	}

	public function testSucess(){

		$mock = new \Chevron\Cache\Drivers\Mock();
		$cache = new Chevron\Cache\Cache($mock, 300);

		$this->assertEquals(false, $cache->success(), "Cache::success() failed");

	}

	public function testMakeKey(){

		$mock = new \Chevron\Cache\Drivers\Mock();
		$cache = new Chevron\Cache\Cache($mock, 300);

		$expected = "9ef50cc82ae474279fb8e82896142702bccbb33a";

		$this->assertEquals($expected, $cache->make_key(array(1,2,3)), "Cache::make_key() failed");

	}

}