<?php

namespace Chevron\HTML;
/**
 * a class for not having to type HTML Form-related tags by hand. includes entity safety
 * @package Chevron\HTML
 */
class Form {

	use \Chevron\Filter\Traits\ToEntitiesTrait;
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
	 * Properly render an Element object as an HTML tag string. The patterns
	 * omit a preceding space for the attribute strings for aesthetic reasons.
	 * @return string
	 */
	function __toString(){
		return vsprintf($this->getPattern(), array(
			$this->marshalTag(),
			$this->marshalAttributes(),
			$this->marshalInnerHTML()
		));
	}

}