<?php

namespace Chevron\Registry;
/**
 * A simple registry used for storing configuration values so they're not
 * floating around in the global space
 */
class Registry {

	protected $map, $success;
	/**
	 * Load an array of data into the Conf registry
	 * @param array $data The data
	 * @return
	 */
	function data(array $data){
		foreach($data as $key => $value){
			$this->map[$key] = $value;
		}
	}
	/**
	 * Get the result of the last operation
	 * @return bool
	 */
	function success(){
		return $this->success === true;
	}
	/**
	 * Get the value stored at $key
	 * @param string $key The key of the value to get
	 * @return mixed
	 */
	function __get($key){
		if(array_key_exists($key, $this->map)){
			$this->success = true;
			return $this->map[$key];
		}
		$this->success = false;
		return null;
	}
	/**
	 * Set the value at $key to $value
	 * @param string $key The key of the value
	 * @param mixed $value The value
	 * @return mixed
	 */
	function __set($key, $value){
		$this->success = true;
		return $this->map[$key] = $value;
	}
	/**
	 * A means to check if a particular data point is set
	 * @param string $name The key of the data to check
	 * @return bool
	 */
	function __isset($name){
		return array_key_exists($name, $this->map);
	}

}