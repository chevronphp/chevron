<?php

namespace Chevron\Stacks;

class FIFO {

	protected $stack = array();

	function push($value){
		$this->stack[] = $value;
	}

	function pop(){
		if($this->length() <= 0) return null;
		return array_shift($this->stack);
	}

	function length(){
		return count($this->stack);
	}

}