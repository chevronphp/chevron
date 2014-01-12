<?php

class MockTest extends PHPUnit_Framework_TestCase {

	public function test_implements(){
		$mock = new \Chevron\PDO\Mock("", "", "");
		$this->assertInstanceOf("\Chevron\PDO\Interfaces\WrapperInterface", $mock, "Mock::__construct failed to return in instance of \Chevron\PDO\Interfaces\WrapperInterface");
	}

	/**
	 * @depends test_implements
	 */
	public function test_next_func(){
		$mock = new \Chevron\PDO\Mock("", "", "");

		$reflection = new \ReflectionClass($mock);
		$next = $reflection->getProperty("next");
		$next->setAccessible(true);

		$expected = array(true, "string", null, 123);

		$mock->next($expected);

		$value = $next->getValue($mock);
		$expected = array($expected);

		$this->assertEquals($expected, $value, "Mock::next failed to set the next property");

	}

	/**
	 * @depends test_implements
	 */
	public function test_next_double_func(){
		$mock = new \Chevron\PDO\Mock("", "", "");

		$reflection = new \ReflectionClass($mock);
		$next = $reflection->getProperty("next");
		$next->setAccessible(true);

		$expected1 = array(true, "string", null, 123);
		$expected2 = array(false, "str", function(){ return 123; });

		$mock->next($expected1);
		$mock->next($expected2);

		$value = $next->getValue($mock);
		$expected = array($expected1, $expected2);

		$this->assertEquals($expected, $value, "Mock::next failed to set the next property");

	}

	/**
	 * @depends test_implements
	 */
	public function test_return_next1(){
		$mock = new \Chevron\PDO\Mock("", "", "");

		$expected = array(true, "string", null, 123);

		$mock->next($expected);

		$value = $mock->scalars("", array());

		$this->assertEquals($expected, $value, "Mock::scalars failed to return the next property");

	}

	/**
	 * @depends test_implements
	 */
	public function test_return_next2(){
		$mock = new \Chevron\PDO\Mock("", "", "");

		$expected = array(true, "string", null, 123);

		$mock->next($expected);

		$value = $mock->insert("", array());

		$this->assertEquals($expected, $value, "Mock::insert failed to return the next property");

	}

	/**
	 * @depends test_implements
	 */
	public function test_return_next_multi(){
		$mock = new \Chevron\PDO\Mock("", "", "");

		$expected1 = array(true, "string", null, 123);
		$expected2 = array(false, "str", function(){ return 123; });

		$mock->next($expected1);
		$mock->next($expected2);

		$value1 = $mock->insert("", array());
		$value2 = $mock->insert("", array());

		$this->assertEquals($expected1, $value1, "Mock::insert failed to return the next property");
		$this->assertEquals($expected2, $value2, "Mock::insert failed to return the next property");

	}

}
