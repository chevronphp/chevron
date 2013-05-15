<?php

namespace Chevron\Stack;

class Stack {

	public $stack = array();

	function push($value){
		return array_push($this->stack, $value);
	}

	function pop(){
		return array_pop($this->stack);
	}

	function shift(){
		return array_shift($this->stack);
	}

	function unshift($value){
		return array_unshift($this->stack, $value);
	}

	function length(){
		return count($this->stack);
	}

}