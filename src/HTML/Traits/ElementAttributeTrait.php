<?php

namespace Chevron\HTML\Traits;

trait ElementAttributeTrait {

	/**
	 * the "attributes" of the current object
	 */
	protected $attributes = array();

	/**
	 * Add an array of attributes to an Element object after it's been instantiated
	 * @param array $map The map of attributes
	 * @return
	 */
	function setAttributes(array $map){
		foreach($map as $key => $value){
			$this->attributes[$key] = $value;
		}
	}

	/**
	 * method to entity-ize a value
	 * @param string $value The value to sanitize
	 * @return string
	 */
	function toEntity($value){
		return htmlentities($value, ENT_QUOTES, "UTF-8");
	}

	/**
	 * method to convert the current attributes to an entitiy-ized string
	 * @return string
	 */
	protected function marshalAttributes(){
		ksort($this->attributes); // if we alphabetize, it's easier to test

		$pairs = " ";
		foreach($this->attributes as $key => $value){
			if(is_null($value)){ continue; }

			if(ctype_digit("{$key}")){
				$pairs .= $this->toEntity($value) . " ";
				continue;
			}

			if(is_array($value)){
				$value = implode(" ", $value);
			}

			if(is_scalar($value)){
				$pairs .= sprintf('%s="%s" ', $key, $this->toEntity($value));
			}
		}
		return rtrim($pairs);
	}
}