<?php

namespace Chevron\HTML;
/**
 * a class for dispatching new HTML objects
 * @package Chevron\HTML
 */
class ElementDispatcher {

	/**
	 * method to dispatch a new HTML\Element object
	 * @param string $tag The tag to create
	 * @param string $innerHTML The innerHTML of the tag
	 * @param array $attributes An arbitrary map of attrs for the element
	 * @return Chevron\HTML\Element
	 */
	function __call( $tag, $args ){
		$innerHTML = "";
		if(array_key_exists(0, $args)){
			$innerHTML = $args[0];
		}

		$attributes = array();
		if(array_key_exists(1, $args)){
			if(is_array($args[1])){
				$attributes = $args[1];
			}
		}

		return new Element($tag, $innerHTML, $attributes);
	}

}