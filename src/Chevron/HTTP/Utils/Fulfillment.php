<?php

namespace Chevron\HTTP\Utils;

/**
 * a class to specify a consistent way to pass information between routers
 * and layouts
 */
class Fulfillment {

	protected $headers;
	protected $layout;
	protected $error;

	function setHeader($key, $value){
		$this->headers[$key] = $value;
	}

	function setLayout($layout){
		$this->layout = $layout;
	}

	function setError(callable $error){
		$this->error = $error;
	}

	function __get($name){
		if(property_exists($this, $name)){
			return $this->$name;
		}
		return null;
	}

}