<?php

namespace Chevron\Container;

class Deferred extends Registry {

	protected $called = array();

	/**
	 * method to call the lambda stored at $tag and pass a payload at invokation
	 * @param string $key The lambda
	 * @param array $args The values to pass to the lambda
	 * @return mixed
	 */
	function invoke($key, array $args = array()){
		if(is_callable($this->map[$key])){
			return call_user_func_array($this->map[$key], $args);
		}
	}

	/**
	 * magic method to call a lambda with the passed payload
	 * @param string $key The lambda
	 * @param array $args The args passed
	 * @return mixed
	 */
	function __call($key, $args){
		return $this->invoke($key, $args);
	}

	/**
	 * method to retrieve a singleton value from the deferred registry
	 * @param string $key The lambda
	 * @param array $args The values to pass to the lambda
	 * @return mixed
	 */
	function once($key, array $args = array()) {

		if(!array_key_exists($key, $this->map) ) {
			return null;
		}

		if(!is_callable($this->map[$key])){
			return $this->map[$key];
		}

		if(!isset($this->called[$key])) {
			$this->called[$key] = $this->invoke($key, $args);
		}

		return $this->called[$key];

	}

}

