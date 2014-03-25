<?php

namespace Chevron\KeyValue;

class BaseKeyValue {

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

}