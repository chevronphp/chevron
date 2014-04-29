<?php

namespace Chevron\HTML;

class Select extends Element {
	/***/
	protected $options, $selected;

	/***/
	function __construct($name, array $options, $selected = '', array $attributes = array()){

		if($name){
			$attributes["name"] = $name;
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

	/***/
	function getPattern(){
		return "<select%s>%s</select>";
	}

	/***/
	function setSelected($value){
		$this->selected = (array)$value;
	}

	/***/
	function setOptions($value){
		$this->options = (array)$value;
	}

	/**
	 * A helper function to create a select drop down form element from an array of information.
	 * This function can take a two level array of options (i.e. array($value => $display) ||
	 * array($label => array($value => $display)).
	 *
	 * @param string $name The name of the select element
	 * @param array $options The array of information with which to construct the options
	 * @param array $attributes An array of attribute value pairs to add to the select tag
	 * @param int $level An argument used for recursion to track how far drilled down the recursion is
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
	 * shortcut method to create a Form object using an alias as the method call
	 * @param string $type The tag as a method call
	 * @param array $args An array of args to be passed to the constructor
	 * @return Form
	 */
	static function __callStatic($name, $args){

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

		$CLASS = __CLASS__;
		return new $CLASS($name, $options, $selected, $attributes);

	}
}



