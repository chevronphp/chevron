<?php
/**
 * There is a limitation in this test in that the IO to a memory buffer
 * only verifies that The class is writing correctly. In a real Redis
 * environment, the buffer woul dhave NEW contents after being written
 * to.
 *
 * Redis::exec can't really be tested because the memory buffer we're
 * using needs to be rewound mid-function. It is however a safe bet that
 * if the protocol passes it's tests that reading/writing should be
 * fine.
 */
class RedisTest extends PHPUnit_Framework_TestCase {

	public function replace_handle($inst, $memory){
		$reflection = new ReflectionClass($inst);
		$handle = $reflection->getProperty("handle");
		$handle->setAccessible(true);
		$handle->setValue($inst, $memory);
		return $inst;
	}

	public function test_call_simple(){

		$redis = new \Chevron\Redis\Redis;

		$memory = fopen("php://memory", "rw+");
		$redis = $this->replace_handle($redis, $memory);

		$redis->sadd("key", "value");

		rewind($memory);
		$result = fread($memory, 36);

		$expected = "*3\r\n$4\r\nsadd\r\n$3\r\nkey\r\n$5\r\nvalue\r\n";

		$this->assertEquals($expected, $result, "Redis::__call (simple) failed to return the proper value");

	}

	public function test_call_complex(){

		$redis = new \Chevron\Redis\Redis;

		$memory = fopen("php://memory", "rw+");
		$redis = $this->replace_handle($redis, $memory);

		$redis->sadd(array("key", "value"));

		rewind($memory);
		$result = fread($memory, 36);

		$expected = "*3\r\n$4\r\nsadd\r\n$3\r\nkey\r\n$5\r\nvalue\r\n";

		$this->assertEquals($expected, $result, "Redis::__call (complex) failed to return the proper value");

	}

	public function test_pipe_simple(){

		$base = array(
			array("sadd", "testkey1", "testvalue1"),
			array("sadd", "testkey2", "testvalue2"),
		);

		$redis = new \Chevron\Redis\Redis;

		$memory = fopen("php://memory", "rw+");
		$redis = $this->replace_handle($redis, $memory);

		$redis->pipe($base);

		rewind($memory);
		$result = fread($memory, 94);

		$expected = "*3\r\n$4\r\nsadd\r\n$8\r\ntestkey1\r\n$10\r\ntestvalue1\r\n\r\n*3\r\n$4\r\nsadd\r\n$8\r\ntestkey2\r\n$10\r\ntestvalue2\r\n\r\n";

		$this->assertEquals($expected, $result, "Redis::pipe (simple) failed to return the proper value");

	}

	public function test_pipe_complex(){

		$base = array(
			array("sadd", array("testkey1", "testvalue1") ),
			array("sadd", array("testkey2", "testvalue2") ),
		);

		$redis = new \Chevron\Redis\Redis;

		$memory = fopen("php://memory", "rw+");
		$redis = $this->replace_handle($redis, $memory);

		$r = $redis->pipe($base);


		rewind($memory);
		$result = fread($memory, 94);

		$expected = "*3\r\n$4\r\nsadd\r\n$8\r\ntestkey1\r\n$10\r\ntestvalue1\r\n\r\n*3\r\n$4\r\nsadd\r\n$8\r\ntestkey2\r\n$10\r\ntestvalue2\r\n\r\n";

		$this->assertEquals($expected, $result, "Redis::pipe (complex) failed to return the proper value");

	}

}