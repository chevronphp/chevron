<?php

namespace Chevron\HTML;
/**
 * a class for dispatching new HTML objects
 * @package Chevron\HTML
 */
class FormDispatcher {

	/**
	 * method to dispatch a new HTML\Form object
	 * @param string $alias The tag
	 * @param string $name The name of the form element
	 * @param string $value The value of the form element
	 * @param bool $checked Whether or not to set the "checked" property
	 * @param array $attributes The attributes of the form element
	 * @return Form
	 */
	function __call( $tag, $args ){
		$name = $value = $checked = "";
		$attributes = array();

		if(array_key_exists(0, $args)){
			$name = $args[0];
		}

		if(array_key_exists(1, $args)){
			$value = $args[1];
		}

		if(array_key_exists(2, $args)){
			$checked = $args[2];
		}

		if(array_key_exists(3, $args)){
			if(is_array($args[3])){
				$attributes = array_merge($attributes, $args[3]);
			}
		}

		return new Form($tag, $name, $value, $checked, $attributes);
	}

}