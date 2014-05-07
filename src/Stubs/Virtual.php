<?php

namespace Chevron\Stubs;
/**
 * implements a virtual widget -- being a widget that uses a lambda instead of
 * a hard file
 *
 * @package Chevron\Stubs
 */
class Virtual extends Widget {
	/**
	 * the callable to use over a hard file
	 */
	protected $callback;

	/**
	 * Set the callback and data map for the Widget
	 * @param string $file The file to render
	 * @param array $data An array of key to value to use in rendering the file
	 * @return object
	 */
	function __construct(callable $callback, array $data = array()){

		$this->callback = $callback;

		if(!empty($data)){
			$this->setMany($data);
		}
	}

	/**
	 * method to call the lambda instead of include the file. the callback is passed
	 * the widget itself to have access to the data and metaData properties
	 * @return mixed
	 */
	function render(){
		return call_user_func($this->callback, $this);
	}

}
