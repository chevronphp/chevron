<?php

namespace Chevron\LevelDB;
/**
 * This is a simple wrapper for the levelDB extension that adds a serializer
 * and implements our caching interface
 *
 * http://reeze.cn/php-leveldb/
 *
 */
class LevelDB extends \LevelDB implements \Chevron\Cache\CacheInterface {
	protected $success;
	protected $serializer, $unserializer;
	/**
	 * For documentation, consult the Interface
	 */
	function success(){
		return $this->success;
	}

	function get( $key ){
		$rst = parent::get( $key );
		$this->success = ($rst === false) ? false : true ;
		$rst = (!is_callable($this->unserializer)) ? $rst : call_user_func($this->unserializer, $rst);
		return $rst;
	}

	function set( $key, $value, $expire = null ){
		$value = (!is_callable($this->serializer)) ? $value : call_user_func($this->serializer, $value);
		$rst = parent::set( $key, $value );
		$this->success = ($rst === false) ? false : true ;
		return $rst;
	}

	function put( $key, $value ){
		return $this->set( $key, $value );
	}

	function setSerializer(callable $serializer){
		$this->serializer = $serializer;
	}

	function setUnSerializer(callable $unserializer){
		$this->unserializer = $unserializer;
	}
	/**
	 * For documentation, consult the Interface
	 */
	function make_key( $key, $hash = "md5" ){
		$key = !is_scalar( $key ) ? json_encode( $key, JSON_NUMERIC_CHECK ) : $key;
		return hash($hash, $key);
	}
}


