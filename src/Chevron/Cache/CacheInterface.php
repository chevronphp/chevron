<?php

namespace Chevron\Cache;
/**
 * @package Chevron\Cache
 * @author Jon Henderson
 */
interface CacheInterface {
	/**
	 * get a value from the cache
	 * @param string $key The key of the item to get
	 * @return bool
	 */
	function get($key);
	/**
	 * cache a value, keep in mind that the library used for cache may or may
	 * not handle object serialization.
	 *
	 * @param string $key The key to store that value at
	 * @param mixed $value The value to store
	 * @param int $expire The number of seconds to cache the value for
	 * @return
	 */
	function set($key, $value, $expire);
	/**
	 * true/false if the last operation was successful as some values maybe
	 * falsey
	 *
	 * @return bool
	 */
	function success();
	/**
	 * create a scalar hash based on non scalar data for use as a cache key
	 * @param mixed $key The data to use in hash creation
	 * @param string $hash The algo to use
	 * @return string
	 */
	function make_key( $key, $hash = "sha1" );
}