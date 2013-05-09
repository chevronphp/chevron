<?php

class MockTest extends PHPUnit_Framework_TestCase {

	public function test_implements(){
		$mock = new \Chevron\PDO\Mysql\Mock("", "", "");
		$this->assertInstanceOf("\Chevron\PDO\Mysql\WrapperInterface", $mock, "Mock::__construct failed to return in instance of \Chevron\PDO\Mysql\WrapperInterface");
	}

	/**
	 * @depends test_implements
	 */
	public function test_next_func(){
		$mock = new \Chevron\PDO\Mysql\Mock("", "", "");

		$reflection = new \ReflectionClass($mock);
		$next = $reflection->getProperty("next");
		$next->setAccessible(true);

		$expected = array(true, "string", null, 123);

		$mock->next($expected);

		$value = $next->getValue($mock);

		$this->assertEquals($expected, $value, "Mock::next failed to set the next property");

	}

	/**
	 * @depends test_implements
	 */
	public function test_return_next1(){
		$mock = new \Chevron\PDO\Mysql\Mock("", "", "");

		$expected = array(true, "string", null, 123);

		$mock->next($expected);

		$value = $mock->scalars("", array());

		$this->assertEquals($expected, $value, "Mock::scalars failed to return the next property");

	}

	/**
	 * @depends test_implements
	 */
	public function test_return_next2(){
		$mock = new \Chevron\PDO\Mysql\Mock("", "", "");

		$expected = array(true, "string", null, 123);

		$mock->next($expected);

		$value = $mock->insert("", array());

		$this->assertEquals($expected, $value, "Mock::insert failed to return the next property");

	}

}