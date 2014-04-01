<?php

namespace Chevron\Container;

class Registry {

	protected $map = array();

	function set($key, $value){
		$this->map[$key] = $value;
	}

	function setMany(array $map){
		foreach($map as $key => $value){
			$this->set($key, $value);
		}
	}

	function get($key){
		if(array_key_exists($key, $this->map)){
			return $this->map[$key];
		}
		return null;
	}

	function has($key){
		return array_key_exists($key, $this->map);
	}

	function length(){
		return count($this->map);
	}

	function getIterator(){
		return new \ArrayIterator($this->map);
	}

}

// ArrayAccess
// public offsetExists ( $offset )
// public offsetGet ( $offset )
// public offsetSet ( $offset, $value )
// public offsetUnset ( $offset )