<?php

namespace Chevron\Container;

class Deferred extends Registry {

	protected $called = array();

	/**
	 * method to call the lambda stored at $tag and pass a payload at invokation
	 * @param string $tag The lambda
	 * @param mixed $args The value to pass to the lambda
	 * @return mixed
	 */
	function invoke($key, array $args = array()){
		return $this->__call($key, $args);
	}

	/**
	 * magic method to call a lambda with the passed payload
	 * @param string $tag The lambda
	 * @param array $args The args passed
	 * @return mixed
	 */
	function __call($key, $args){
		if(is_callable($this->map[$key])){
			return call_user_func_array($this->map[$key], $args);
		}
	}

	/**
	 * method to retrieve a singleton value from the deferred registry
	 * @return mixed
	 */
	function once($key, array $args) {

		if(!array_key_exists($key, $this->map) ) {
			return null;
		}

		// if( !isset($this->map[$key]) ) return null;

		if(!is_callable($this->map[$key])){
			return $this->map[$key];
		}

		if(!isset($this->called[$key])) {
			$this->called[$key] = call_user_func_array($this->map[$key], $args);
		}

		return $this->called[$key];

	}

}

