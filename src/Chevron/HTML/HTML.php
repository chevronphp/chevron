<?php

namespace Chevron\HTML;

class HTML extends Element {
	/**
	 * Shortcut to generating an HTML tag string
	 * @param string $tag The HTML tag
	 * @param array $args The innerHTML, attributes (in that order)
	 * @return Chevron\HTML\Element
	 */
	public static function __callStatic($tag, $args){
		if( !ctype_alpha($tag) ){
			throw new \Exception("Tag must be an alpha string");
		}

		$innerHTML  = "";
		$attributes = array();

		if(array_key_exists(0, $args)){
			$innerHTML = (string)$args[0];
		}

		if(array_key_exists(1, $args)){
			$attributes = (array)$args[1];
		}

		$self = __CLASS__;
		$el   = new $self($tag, $attributes);
		$el->innerHTML($innerHTML);
		return $el;

	}

}