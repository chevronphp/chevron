<?php

namespace Chevron\Stub;

class Widget extends \Chevron\Registry\Registry {

	protected $file;
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
	 * Require, and thus render, a file
	 */
	function render(){
		require($this->file);
	}

}