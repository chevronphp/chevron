<?php

namespace Chevron\Stubs;

class Widget implements WidgetInterface {

	protected $file;
	protected $map  = array();
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
			$this->setData($data);
		}
	}
	/**
	 * Load an array of data into the Conf registry
	 * @param array $data The data
	 * @return
	 */
	function setData(array $data){
		foreach($data as $key => $value){
			$this->map[$key] = $value;
		}
	}
	/**
	 * Require, and thus render, a file
	 */
	function render(){
		return require($this->file);
	}
	/**
	 * Method to make this class callable
	 * @return type
	 */
	function __invoke(){
		return $this->render();
	}
	/**
	 * Get the value stored at $key
	 * @param string $key The key of the value to get
	 * @return mixed
	 */
	function __get($key){
		if(!array_key_exists($key, $this->map)) return null;
		return $this->map[$key];
	}
	/**
	 * A means to check if a particular data point is set
	 * @param string $name The key of the data to check
	 * @return bool
	 */
	function __isset($name){
		return array_key_exists($name, $this->map);
	}
	/**
	 * method to return the widget as a string ...
	 * @return string
	 */
	function __toString(){
		ob_start();
		$this->render();
		return ob_get_clean();
	}

}
