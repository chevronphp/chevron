<?php

namespace Chevron\HTML;
/**
 * a class for not having to type HTML Form-related tags by hand. includes entity safety
 * @package Chevron\HTML
 */
class Form {

	use Traits\ElementAttributeTrait;
	use Traits\ElementInnerHTMLTrait;
	use Traits\ElementPatternTrait;
	use Traits\ElementRenderTrait;
	use Traits\ElementTagTrait;

	/**
	 * method to create a Form HTML element
	 * @param string $alias The tag
	 * @param string $name The name of the form element
	 * @param string $value The value of the form element
	 * @param bool $checked Whether or not to set the "checked" property
	 * @param array $attributes The attributes of the form element
	 * @return Form
	 */
	function __construct( $alias, $name, $value = "", $checked = null, array $attributes = [] ){

		if($type = $this->getAlias($alias)){
			$this->setTag("input");
			$this->setAttributes(["type" => $type]);
		}else{
			$this->setTag($alias);
		}

		if($checked){
			$this->setChecked($checked);
		}

		if($name){
			$this->setAttributes(["name" => $name]);
		}

		if($value){
			$this->setValue($value);
			if(in_array($alias, ["button", "textarea"])){
				$this->setInnerHTML($value);
			}
		}

		if($attributes){
			$this->setAttributes($attributes);
		}
	}

	/**
	 * method to set the value of the current object
	 * @param string $value The value to set
	 * @return
	 */
	function setValue($value){
		$this->setAttributes(["value" => $value]);
	}

	/**
	 * method to set the checked property of the current object
	 * @param bool $checked True/False to set the checked property
	 * @return
	 */
	function setChecked($checked){
		if($checked){
			$this->setAttributes(["checked" => "checked"]);
		}else{
			$this->setAttributes(["checked" => null]);
		}
	}

	/**
	 * shortcut method to create a Form object using an alias as the method call
	 * @param string $type The tag as a method call
	 * @param array $args An array of args to be passed to the constructor
	 * @return Form
	 */
	// static function __callStatic($type, $args){

	// 	$name = $value = $checked = "";
	// 	$attributes = array();

	// 	if(array_key_exists(0, $args)){
	// 		$name = $args[0];
	// 	}

	// 	if(array_key_exists(1, $args)){
	// 		$value = $args[1];
	// 	}

	// 	if(array_key_exists(2, $args)){
	// 		$checked = $args[2];
	// 	}

	// 	if(array_key_exists(3, $args)){
	// 		if(is_array($args[3])){
	// 			$attributes = array_merge($attributes, $args[3]);
	// 		}
	// 	}

	// 	$CLASS = __CLASS__;
	// 	return new $CLASS($type, $name, $value, $checked, $attributes);

	// }
}