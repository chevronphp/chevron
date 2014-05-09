<?php

namespace Chevron\Container;
/**
 * implementation of the registry pattern that supports lambdas over/against
 * scalar values
 *
 * @package Chevron\Container
 */
class Deferred extends Registry {

	/**
	 * an additional map for storing the return of singleton lambdas
	 */
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
	 * Method to retrieve the value stored at key. Allows for Di style lambda or
	 * scalar retrieval.
	 * @param string $key The key of the value to retrieve
	 * @param bool $new A toggle to call the callable anew
	 * @return mixed
	 */
	function get($key, $new = false){
		if(!array_key_exists($key, $this->map)){ return null; }

		if(is_callable($this->map[$key])){
			if($new){
				return $this->invoke($key);
			}
			return $this->once($key);
		}
		return $this->map[$key];
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

