<?php

namespace Chevron\Stub;

class Widget {

	protected $file, $data_map;
	/**
	 * Set the file and data map for the Widget
	 * @param string $file The file to render
	 * @param array $data An array of key to value to use in rendering the file
	 * @return object
	 */
	function __construct($file, array $data = array()){
		if( file_exists($file) ){
			$this->file = $file;
		}else{
			throw new \Exception("Widget::__construct cannot render an empty file.");
		}

		if(!empty($data)){
			$this->data($data);
		}
	}
	/**
	 * Set the various data to be scoped within the widget rendering
	 * @param array $map The data to scope within the widget
	 */
	function data(array $map){
		foreach($map as $key => $value){
			$this->data_map[$key] = $value;
		}
	}
	/**
	 * A means to check if a particular data point is set
	 * @param string $name The key of the data to check
	 * @return bool
	 */
	function __isset($name){
		return array_key_exists($name, $this->data_map);
	}
	/***/
	function __get($name){
		if(array_key_exists($name, $this->data_map)){
			return $this->data_map[$name];
		}
		return null;
	}
	/**
	 * Require, and thus render, a file
	 */
	function render(){
		require($this->file);
	}

}