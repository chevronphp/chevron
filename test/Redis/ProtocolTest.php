<?php

class ProtocolTest extends PHPUnit_Framework_TestCase {

	public function get_handle( $string ){
		$handle = fopen("php://memory", "rw+");
		fwrite($handle, $string);
		rewind($handle);
		return $handle;
	}

	public function test_pull_null(){

		$handle = $this->get_handle("$-1");
		$redis = new \Chevron\Redis\Redis;

		$reflection = new ReflectionClass($redis);
		$pull = $reflection->getMethod("pull");
		$pull->setAccessible(true);

		$bytes = $pull->invoke( $redis, $handle, "-1" );
		$expected = null;

		$this->assertEquals($expected, $bytes, "Redis::pull (null) failed to return the number of bytes written");

	}

	public function test_pull_small(){

		$handle = $this->get_handle("*4\r\n$4\r\nabcd\r\n$4\r\nefgh\r\n$4\r\nijklm\r\n$4\r\nnopq");
		$redis = new \Chevron\Redis\Redis;

		$reflection = new ReflectionClass($redis);
		$pull = $reflection->getMethod("pull");
		$pull->setAccessible(true);

		$bytes = $pull->invoke( $redis, $handle, 16 );
		$expected = "*4\r\n$4\r\nabcd\r\n$4";

		$this->assertEquals($expected, $bytes, "Redis::pull (small) failed to return the number of bytes written");

	}

	public function test_pull_large(){

		$str = str_repeat("*4\r\n$4\r\nabcd\r\n$4\r\nefgh\r\n$4\r\nijklm\r\n$4\r\nnopq", 500);

		$handle = $this->get_handle($str);
		$redis = new \Chevron\Redis\Redis;

		$reflection = new ReflectionClass($redis);
		$pull = $reflection->getMethod("pull");
		$pull->setAccessible(true);

		$bytes = $pull->invoke( $redis, $handle, 1500 );
		$expected = substr($str, 0, 1500);

		$this->assertEquals($expected, $bytes, "Redis::pull (large) failed to return the number of bytes written");

	}

	public function test_protocol_simple(){

		$base = array("sadd", "testkey", "testvalue");

		$redis = new \Chevron\Redis\Redis;

		$reflection = new ReflectionClass($redis);
		$protocol = $reflection->getMethod("protocol");
		$protocol->setAccessible(true);

		$string = $protocol->invoke( $redis, $base );
		$expected = "*3\r\n$4\r\nsadd\r\n$7\r\ntestkey\r\n$9\r\ntestvalue\r\n";

		// $this->assertEquals(1, $count, "Redis::protocol (simple) failed to return the proper number of commands");
		$this->assertEquals($expected, $string, "Redis::protocol (simple) failed to return a properly formatted string");

	}

	public function test_protocol_complex(){

		$base = array("sadd", array("testkey", "testvalue") );

		$redis = new \Chevron\Redis\Redis;

		$reflection = new ReflectionClass($redis);
		$protocol = $reflection->getMethod("protocol");
		$protocol->setAccessible(true);

		$string = $protocol->invoke( $redis, $base );
		$expected = "*3\r\n$4\r\nsadd\r\n$7\r\ntestkey\r\n$9\r\ntestvalue\r\n";

		// $this->assertEquals(2, $count, "Redis::protocol (complex) failed to return the proper number of commands");
		$this->assertEquals($expected, $string, "Redis::protocol (complex) failed to return a properly formatted string");

	}

	public function test_write(){

		$handle = fopen("php://memory", "rw+");

		$string = "*1\r\n$4\r\nPING\r\n";

		$redis = new \Chevron\Redis\Redis;

		$reflection = new ReflectionClass($redis);
		$write = $reflection->getMethod("write");
		$write->setAccessible(true);

		$bytes = $write->invoke( $redis, $handle, $string );
		$expected = 14;

		$this->assertEquals($expected, $bytes, "Redis::write failed to return the number of bytes written");

	}

	public function test_read_single(){

		$handle = $this->get_handle("+OK");
		$redis = new \Chevron\Redis\Redis;

		$reflection = new ReflectionClass($redis);
		$read = $reflection->getMethod("read");
		$read->setAccessible(true);

		$bytes = $read->invoke( $redis, $handle, 1 );
		$expected = array("OK");

		$this->assertEquals($expected, $bytes, "Redis::read (single) failed to return the number of bytes written");

	}

	public function test_read_int(){

		$handle = $this->get_handle(":1234");
		$redis = new \Chevron\Redis\Redis;

		$reflection = new ReflectionClass($redis);
		$read = $reflection->getMethod("read");
		$read->setAccessible(true);

		$bytes = $read->invoke( $redis, $handle, 1 );
		$expected = array("1234");

		$this->assertEquals($expected, $bytes, "Redis::read (int) failed to return the number of bytes written");

	}

	/**
	 * @expectedException Exception
	 */
	public function test_read_err(){

		$handle = $this->get_handle("-ERR Operation not permitted");
		$redis = new \Chevron\Redis\Redis;

		$reflection = new ReflectionClass($redis);
		$read = $reflection->getMethod("read");
		$read->setAccessible(true);

		$bytes = $read->invoke( $redis, $handle, 1 );

	}

	public function deprecated_read_err(){

		$handle = $this->get_handle("-ERR Operation not permitted");
		$redis = new \Chevron\Redis\Redis;

		$reflection = new ReflectionClass($redis);
		$read = $reflection->getMethod("read");
		$read->setAccessible(true);

		$bytes = $read->invoke( $redis, $handle, 1 );
		$expected = array("ERR Operation not permitted");

		$this->assertEquals($expected, $bytes, "Redis::read (err) failed to return the number of bytes written");

	}

	public function test_read_bulk(){

		$handle = $this->get_handle("$4\r\nPONG\r\n");
		$redis = new \Chevron\Redis\Redis;

		$reflection = new ReflectionClass($redis);
		$read = $reflection->getMethod("read");
		$read->setAccessible(true);

		$bytes = $read->invoke( $redis, $handle, 1 );
		$expected = array("PONG");

		$this->assertEquals($expected, $bytes, "Redis::read (bulk) failed to return the number of bytes written");

	}

	public function test_read_bulk_empty(){

		$handle = $this->get_handle("$0\r\n\r\n");
		$redis = new \Chevron\Redis\Redis;

		$reflection = new ReflectionClass($redis);
		$read = $reflection->getMethod("read");
		$read->setAccessible(true);

		$bytes = $read->invoke( $redis, $handle, 1 );
		$expected = array("");

		$this->assertEquals($expected, $bytes, "Redis::read (empty bulk) failed to return the number of bytes written");

	}

	public function test_read_null_bulk(){

		$handle = $this->get_handle("$-1\r\n");
		$redis = new \Chevron\Redis\Redis;

		$reflection = new ReflectionClass($redis);
		$read = $reflection->getMethod("read");
		$read->setAccessible(true);

		$bytes = $read->invoke( $redis, $handle, 1 );
		$expected = array(null);

		$this->assertEquals($expected, $bytes, "Redis::read (null bulk) failed to return the number of bytes written");

	}

	public function test_read_multi_bulk(){

		$handle = $this->get_handle("*2\r\n$4\r\nPING\r\n$4\r\nPONG\r\n");
		$redis = new \Chevron\Redis\Redis;

		$reflection = new ReflectionClass($redis);
		$read = $reflection->getMethod("read");
		$read->setAccessible(true);

		$bytes = $read->invoke( $redis, $handle, 1 );
		$expected = array(array("PING", "PONG"));

		$this->assertEquals($expected, $bytes, "Redis::read (multi bulk) failed to return the number of bytes written");

	}


	public function test_read_multi_multi_bulk(){

		$handle = $this->get_handle("$4\r\nPING\r\n$4\r\nPONG\r\n");
		$redis = new \Chevron\Redis\Redis;

		$reflection = new ReflectionClass($redis);
		$read = $reflection->getMethod("read");
		$read->setAccessible(true);

		$bytes = $read->invoke( $redis, $handle, 2 );
		$expected = array("PING", "PONG");

		$this->assertEquals($expected, $bytes, "Redis::read (multi multi bulk) failed to return the number of bytes written");

	}

	public function test_read_nested_multi_bulk(){

		$handle = $this->get_handle("*2\r\n*2\r\n$4\r\nPING\r\n$4\r\nPONG\r\n*2\r\n$4\r\nPING\r\n$4\r\nPONG\r\n");
		$redis = new \Chevron\Redis\Redis;

		$reflection = new ReflectionClass($redis);
		$read = $reflection->getMethod("read");
		$read->setAccessible(true);

		$bytes = $read->invoke( $redis, $handle, 1 );
		$expected = array(array(array("PING", "PONG"), array("PING", "PONG")));

		$this->assertEquals($expected, $bytes, "Redis::read (nested multi bulk) failed to return the number of bytes written");

	}

	public function test_read_mixed_multi_bulk(){

		$handle = $this->get_handle("*2\r\n*2\r\n$4\r\nPING\r\n$4\r\nPONG\r\n$8\r\nPINGPONG\r\n");
		$redis = new \Chevron\Redis\Redis;

		$reflection = new ReflectionClass($redis);
		$read = $reflection->getMethod("read");
		$read->setAccessible(true);

		$bytes = $read->invoke( $redis, $handle, 1 );
		$expected = array(array(array("PING", "PONG"), "PINGPONG"));

		$this->assertEquals($expected, $bytes, "Redis::read (mixed multi bulk) failed to return the number of bytes written");

	}

}