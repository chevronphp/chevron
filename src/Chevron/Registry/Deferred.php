<?php

namespace Chevron\Registry;

class Deferred {

	protected $callables, $payloads;

	/**
	 * method to add a lambda to a lambda registry
	 * @param string $tag A key to use to call the callback
	 * @param callable $callable The lambda to call
	 * @param mixed $payload An opional payload to pass when the lambda is called
	 * @return mixed
	 */
	function register($tag, callable $callable, $payload = null){
		$this->callables[$tag] = $callable;
		$this->payloads[$tag] = $payload;
	}

	/**
	 * method to call the lambda stored at $tag with the associate payload
	 * @param string $tag The lambda
	 * @return mixed
	 */
	function invoke($tag){
		if($this->callables[$tag]){
			return call_user_func($this->callables[$tag], $this->payloads[$tag]);
		}
	}

	/**
	 * method to call the lambda stored at $tag and pass a payload at invokation
	 * @param string $tag The lambda
	 * @param mixed $args The value to pass to the lambda
	 * @return mixed
	 */
	function invokeArgs($tag, $args = null){
		if( !empty($args) ) {
			$this->payloads[$tag] = $args;
		}
		return $this->invoke($tag);
	}

	/**
	 * magic method to call a lambda with the passed payload
	 * @param string $tag The lambda
	 * @param array $args The args passed
	 * @return mixed
	 */
	function __call($tag, $args = array()){
		return $this->invokeArgs($tag, reset($args));
	}

}