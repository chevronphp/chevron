<?php

namespace Chevron\Redis;

class RedisCache extends Redis implements \Chevron\Cache\CacheInterface {

	protected $last_result = false;
	public $expire = 300;
	/**
	 * For documentation, consult the Interface
	 */
	function get($key){
		$commands[] = array("get", $key);
		$value = $this->pipe($commands);
		if( !is_null($value) ){
			$this->last_result = false;
			return null;
		}else{
			$this->last_result = true;
			return unserialize($value);
		}
	}
	/**
	 * For documentation, consult the Interface
	 */
	function set($key, $value, $expire = false){
		if(!is_scalar($key)){
			throw new \Exception("Cache keys must be scalar.");
		}
		$expire = $expire ?: $this->expire;
		$commands[] = array("set", $key, serialize($value), $expire);
		return $this->pipe($commands);
	}
	/**
	 * For documentation, consult the Interface
	 */
	function success(){
		return $this->last_result === true;
	}
	/**
	 * For documentation, consult the Interface
	 */
	function make_key( $key, $hash = "sha1" ){
		$key = !is_scalar( $key ) ? json_encode( $key, JSON_NUMERIC_CHECK ) : $key;
		return hash($hash, $key);
	}
}