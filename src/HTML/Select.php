<?php

namespace Chevron\HTML;
/**
 * a class for not having to select tags by hand. includes entity safety
 * @package Chevron\HTML
 */
class Select {

	use Traits\ElementAttributeTrait;
	use Traits\ElementInnerHTMLTrait;
	use Traits\ElementRenderTrait;
	use Traits\ElementTagTrait;

	/**
	 * an array of options for the select tag
	 */
	protected $options;

	/**
	 * an array of selected options
	 */
	protected $selected;

	/**
	 * method to create an element that is stringified to a select tag
	 * @param string $name The name of the select tag
	 * @param array $options An array of options/optgroups
	 * @param mixed $selected The options to mark selected
	 * @param array $attributes An array of attrs for the select tag
	 * @return Chevron\HTML\Select
	 */
	function __construct($name, array $options, $selected = '', array $attributes = []){

		if($name){
			$this->setAttributes(["name" => $name]);
		}

		if($attributes){
			$this->setAttributes($attributes);
		}

		if($options){
			$this->setOptions($options);
		}

		if($selected){
			$this->setSelected($selected);
		}
	}

	/**
	 * Properly render an Element object as an HTML tag string. The patterns
	 * omit a preceding space for the attribute strings for aesthetic reasons.
	 * @return string
	 */
	function __toString(){

		return vsprintf($this->getPattern(), array(
			$this->marshalAttributes(),
			$this->marshalOptions()
		));

	}

	/**
	 * method to return the sprintf pattern to use for the select tag
	 * @return string
	 */
	function getPattern(){
		return "<select%s>%s</select>";
	}

	/**
	 * method to set the selected values for the select tag
	 * @param mixed $value The value -- will be cast to array
	 * @return
	 */
	function setSelected($value){
		$this->selected = (array)$value;
	}

	/**
	 * method to set the options/optgroups for the select tag
	 * @param mixed $value The value -- will be cast to array
	 * @return
	 */
	function setOptions($value){
		$this->options = (array)$value;
	}

	/**
	 * method to organize the options/optgroups
	 * @param array $options The array of options/optgroups
	 * @return string
	 */
	function marshalOptions(array $options = array()){
		$options = $options ?: $this->options;

		$opts = "";
		foreach($options as $key => $value){
			if(is_array($value)){
				$_options = $this->marshalOptions($value);
				$temp = sprintf('<optgroup label="%s">%s</optgroup>', $this->toEntity($key), $_options);
			}else{
				$selected = "";
				if(in_array($key, $this->selected)){
					$selected = ' selected="selected"';
				}

				$temp = sprintf(
					"<option value=\"%s\"%s>%s</option>",
					$this->toEntity($key),
					$selected,
					$this->toEntity($value)
				);
			}
			$opts .= $temp;
		}
		return $opts;
	}

	/**
	 * shortcut method to create a select object using an alias as the method call
	 * @param string $name The select name as a method call
	 * @param array $args An array of args to be parsed and passed to the constructor
	 * @return Chevron\HTML\Select
	 */
	// static function __callStatic($name, $args){

	// 	$selected = "";
	// 	$options  = $attributes = array();

	// 	if(array_key_exists(0, $args)){
	// 		if(is_array($args[0])){
	// 			$options = $args[0];
	// 		}
	// 	}

	// 	if(array_key_exists(1, $args)){
	// 		$selected = $args[1];
	// 	}

	// 	if(array_key_exists(2, $args)){
	// 		if(is_array($args[2])){
	// 			$attributes = $args[2];
	// 		}
	// 	}

	// 	$CLASS = __CLASS__;
	// 	return new $CLASS($name, $options, $selected, $attributes);

	// }
}



