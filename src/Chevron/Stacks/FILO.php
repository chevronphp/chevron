<?php

namespace Chevron\Stacks;

class FILO {

	protected $stack = array();

	function push($value){
		$this->stack[] = $value;
	}

	function pop(){
		if($this->length() <= 0) return null;
		return array_pop($this->stack);
	}

	function length(){
		return count($this->stack);
	}

}