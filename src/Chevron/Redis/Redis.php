<?php

namespace Chevron\Redis;

/**
 * Class to talk to redis at a low level ... http://redis.io/topics/protocol
 *
 * Shoulders of giants: https://github.com/kylebragger/iRedis/blob/master/iredis.php
 * Shoulders of giants: https://github.com/jdp/redisent
 */
class Redis extends Protocol {
	/**
	 * Magic method to allow devs to use Redis methods via php
	 *
	 * @param string $func The redis function to execute
	 * @param array $args An array of the arguments passed
	 * @return mixed
	 */
	function __call($func, $args = false){

		$iter1 = new \RecursiveArrayIterator(func_get_args());
		$iter2 = new \RecursiveIteratorIterator($iter1);

		for($iter2->rewind(); $iter2->valid(); $iter2->next()){
			$command[] = $iter2->current();
		}

		return $this->pipe( $command );
	}
}