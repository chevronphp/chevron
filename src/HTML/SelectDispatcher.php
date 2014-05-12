<?php

namespace Chevron\HTML;
/**
 * a class for dispatching new HTML objects
 * @package Chevron\HTML
 */
class SelectDispatcher {

	/**
	 * method to dispatch a new HTML\Select object
	 * @param string $name The name of the select tag
	 * @param array $options An array of options/optgroups
	 * @param mixed $selected The options to mark selected
	 * @param array $attributes An array of attrs for the select tag
	 * @return Chevron\HTML\Select
	 */
	function __call( $name, $args ){
		$selected = "";
		$options  = $attributes = array();

		if(array_key_exists(0, $args)){
			if(is_array($args[0])){
				$options = $args[0];
			}
		}

		if(array_key_exists(1, $args)){
			$selected = $args[1];
		}

		if(array_key_exists(2, $args)){
			if(is_array($args[2])){
				$attributes = $args[2];
			}
		}

		return new Select($name, $options, $selected, $attributes);
	}

}