<?php

namespace Chevron\Stubs;

class Widget implements WidgetInterface {

	protected $file;
	protected $__meta = array();
	protected $__map  = array();
	/**
	 * Set the file and data map for the Widget
	 * @param string $file The file to render
	 * @param array $data An array of key to value to use in rendering the file
	 * @return object
	 */
	function __construct($file, array $data = array(), array $meta = array()){
		if( file_exists($file) ){
			$this->file = $file;
		}else{
			throw new \Exception("Widget::__construct cannot render an empty file.");
		}

		if(!empty($data)){
			$this->loadData($data);
		}

		if(!empty($meta)){
			$this->setMeta($meta);
		}
	}

	/**
	 * Load an array of data into the Conf registry
	 * @param array $data The data
	 * @return
	 */
	function loadData(array $data){
		foreach($data as $key => $value){
			$this->__map[$key] = $value;
		}
	}

	/**
	 * Require, and thus render, a file
	 */
	function render(){
		return require($this->file);
	}

	/**
	 * Method to store additional information so that a widget can be
	 * self aware without conflicting with the scoped data
	 * @param array $data Metadata
	 * @return
	 */
	function setMeta(array $data){
		foreach($data as $key => $value){
			$this->__meta[$key] = $value;
		}
	}

	/**
	 * Method to retrieve metadata
	 * @param string $name The metadata to get
	 * @return mixed
	 */
	function getMeta($key){
		if(!array_key_exists($key, $this->__meta)) return null;
		return $this->__meta[$key];
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
		if(!array_key_exists($key, $this->__map)) return null;
		return $this->__map[$key];
	}

	/**
	 * Set the value at $key to $value
	 * @param string $key The key of the value
	 * @param mixed $value The value
	 * @return mixed
	 */
	function __set($key, $value){
		return $this->__map[$key] = $value;
	}

	/**
	 * A means to check if a particular data point is set
	 * @param string $name The key of the data to check
	 * @return bool
	 */
	function __isset($name){
		return array_key_exists($name, $this->__map);
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
