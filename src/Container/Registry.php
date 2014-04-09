<?php

namespace Chevron\Container;

class Registry implements \Countable {
	/**
	 * The underlying storage array
	 */
	protected $map = array();
	/**
	 * Method to set a single value in the registry
	 * @param scalar $key The key at which to store the value
	 * @param mixed $value The value to store
	 * @return
	 */
	function set($key, $value){
		$this->map[$key] = $value;
	}
	/**
	 * Method to set many values in the registry
	 * @param array $map The map of key => values
	 * @return
	 */
	function setMany(array $map){
		foreach($map as $key => $value){
			$this->set($key, $value);
		}
	}
	/**
	 * Method to retrieve the value stored at key
	 * @param scalar $key The key of the value to retrieve
	 * @return mixed
	 */
	function get($key){
		if(array_key_exists($key, $this->map)){
			return $this->map[$key];
		}
		return null;
	}
	/**
	 * Method to determine if the registry has a key
	 * @param scalar $key The key to check
	 * @return bool
	 */
	function has($key){
		return array_key_exists($key, $this->map);
	}
	/**
	 * Method to retrieve the number of values in the registry
	 * @return type
	 */
	function length(){
		return count($this->map);
	}
	/**
	 * Method to get an Iterator for the registry, allows looping
	 * @return ArrayIterator
	 */
	function getIterator(){
		return new \ArrayIterator($this->map);
	}
	/**
	 * Method to implement \Countable on the registry
	 * @link http://php.net/manual/en/countable.count.php
	 * @return int
	 */
	function count() {
		return $this->length();
	}
}

// ArrayAccess
// public offsetExists ( $offset )
// public offsetGet ( $offset )
// public offsetSet ( $offset, $value )
// public offsetUnset ( $offset )