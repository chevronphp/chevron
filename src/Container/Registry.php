<?php

namespace Chevron\Container;

class Registry implements \Countable {

	protected $map = array();

	// function __construct( &$map = null ) {
	// 	if( $map === null ) {
	// 		$map = array();
	// 	}

	// 	$this->map =& $map;
	// }

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

	function count() {
		return $this->length();
	}

}

// ArrayAccess
// public offsetExists ( $offset )
// public offsetGet ( $offset )
// public offsetSet ( $offset, $value )
// public offsetUnset ( $offset )