<?php

namespace Chevron\HTML;

class Form extends Element {
	/**
	 * Array of aliases to allow for input creation via type
	 */
	protected static $tag_aliases = array(
		"text"     =>  array("tag" => "input", "type" => "text"),
		"search"   =>  array("tag" => "input", "type" => "search"),
		"password" =>  array("tag" => "input", "type" => "password"),
		"submit"   =>  array("tag" => "input", "type" => "submit"),
		"reset"    =>  array("tag" => "input", "type" => "reset"),
		"hidden"   =>  array("tag" => "input", "type" => "hidden"),
		"checkbox" =>  array("tag" => "input", "type" => "checkbox"),
		"radio"    =>  array("tag" => "input", "type" => "radio"),
		"button"   =>  array("tag" => "button", "type" => false),
		"textarea" =>  array("tag" => "textarea", "type" => false),
	);

	/**
	 * Factory method to allow for Element creation. Args are passed this order:
	 * Form::radio($name, $value, $checked, $attributes);
	 *
	 * @param string $tag The tag
	 * @param array $args The args passed to the method
	 * @return Chevron\HTML\Element
	 */
	static function __callStatic($tag, $args){

		if( !ctype_alpha($tag) ){
			throw new \Exception("Tag must be an alpha string");
		}

		$attributes = array_fill_keys(array("name", "value"), "");

		if( array_key_exists(0, $args) ){
			$attributes["name"] = $args[0];
		}

		if( array_key_exists(1, $args) ){
			$attributes["value"] = $args[1];
		}

		if( array_key_exists(2, $args) ){
			$attributes["checked"] = (bool)$args[2] ? 'checked' : null;
		}

		if( array_key_exists(3, $args) ){
			$attributes = array_merge($attributes, (array)$args[3]);
		}

		$innerHTML = "";
		switch( $tag ){
			case( "button" ):
				$innerHTML = $attributes["value"];
			case( "textarea" ):
				$innerHTML = $attributes["value"];
				$attributes["value"] = null;
			break;
		}

		if( array_key_exists($tag, self::$tag_aliases) ){
			$attributes["type"] = self::$tag_aliases[$tag]["type"];
			$tag                = self::$tag_aliases[$tag]["tag"];
		}

		if( !array_key_exists("type", $attributes) ){
			throw new \Exception("Form tags must have a type");
		}

		$self = __CLASS__;
		$el   = new $self($tag, $attributes);

		if(!empty($innerHTML)){
			$el->innerHTML($innerHTML);
		}

		return $el;
	}

	/**
	 * Create an arrayed HTML Form name from an array
	 * @param array $name The array of names to combine
	 * @return string
	 */
	static function arrayify_name(array $name){
		$nameSTR = str_repeat("[%s]", ( count($name) - 1 ) );
		return vsprintf("%s{$nameSTR}", $name);
		// return vsprintf("name=\"%s{$nameSTR}\"", $name);
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
	static function select($name, array $options, $selected = '', array $attributes = array(), $level = 1){
		if(!is_array($options)){ return ""; }

		$opts = $attrs = '';

		foreach($options as $key => $value){
			if(is_array($value)){
				$temp = sprintf('<optgroup label="%s">%s</optgroup>', $key, self::select("", $value, $selected, array(), $level + 1));
			}else{
				$sel = in_array($key, (array)$selected) ? ' selected="selected"' : "";
				$temp = sprintf('<option value="%s"%s>%s</option>', $key, $sel, $value);
			}
			$opts .= $temp;
		}

		$attrs = array();

		if(!empty($name)){
			$attrs = array("name" => $name);
		}

		if(!empty($attributes)){
			$attrs = array_merge($attrs, $attributes);
		}

		$attrs = static::stringify_attrs($attrs);

		return ($level > 1) ? $opts : sprintf('<select %s>%s</select>', $attrs, $opts);
	}

}

