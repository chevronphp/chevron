<?php

namespace Chevron\Cache;

class Mock implements CacheInterface {
	/**
	 * For documentation, consult the Interface
	 */
	function get($key){
		return null;
	}
	/**
	 * For documentation, consult the Interface
	 */
	function set($key, $value, $expire = false){
		return true;
	}
	/**
	 * For documentation, consult the Interface
	 */
	function success(){
		return false;
	}
	/**
	 * For documentation, consult the Interface
	 */
	function make_key( $key, $hash = "sha1" ){
		$key = !is_scalar( $key ) ? json_encode( $key, JSON_NUMERIC_CHECK ) : $key;
		return hash($hash, $key);
	}
}