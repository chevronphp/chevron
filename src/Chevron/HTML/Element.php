<?php

namespace Chevron\HTML;

class Element {

	protected $tag,
		$innerHTML,
		$attributes;

	protected $isSelfClosing, $isEmpty;
	/**
	 * Create an Element object that will stringify to an HTML tag
	 * @param string $selector A CSS selector string of attrs for the element
	 * @param array $map An arbitrary map of attrs for the element
	 * @return Chevron\HTML\Element
	 */
	function __construct( $tag ){
		if( !ctype_alpha( $tag ) ){ return null; }
		$this->tag = $tag;
	}
	/**
	 * Add an array of attributes to an Element object after it's been instantiated
	 * @param array $map The map of attributes
	 * @return
	 */
	function setAttributes(array $map){
		foreach($map as $key => $value){
			if(!$value) continue;
			$this->attributes[$key] = $value;
		}
	}
	/**
	 * Add content to an Element object
	 * @param scalar $innerHTML The content to add
	 * @return
	 */
	function setInnerHTML($innerHTML){
		if(!is_scalar($innerHTML)){
			throw new \Exception("Element innerHTML must be scalar");
		}
		$this->innerHTML = htmlentities($innerHTML, ENT_QUOTES, "UTF-8");
	}
	/**
	 * toggle the pattern used in __toString()
	 */
	function setSelfClosing($bool){
		$this->isSelfClosing = (bool)$bool;
	}
	/**
	 * toggle the pattern used in __toString()
	 */
	function setEmpty($bool){
		$this->isEmpty = (bool)$bool;
	}
	/**
	 * Method to allow readonly access to properties
	 * @param string $attribute The attribute to return
	 * @return type
	 */
	function __get($attribute){
		if( property_exists($this, $attribute) ){
			return $this->$attribute;
		}

		if( array_key_exists($attribute, $this->attributes) ){
			return $this->attributes[$attribute];
		}
	}
	/**
	 * Properly render an Element object as an HTML tag string. The patterns
	 * omit a preceding space for the attribute strings for aesthetic reasons.
	 * @return string
	 */
	function __toString(){
		$pattern = '<%1$s%2$s>%3$s</%1$s>';

		if( $this->isEmpty ){
			$pattern = '<%1$s%2$s></%1$s>';
		}

		if( $this->isSelfClosing ){
			$pattern = '<%1$s%2$s />';
		}

		$tag = $attributes = $innerHTML = "";

		$tag = $this->tag;

		if( $this->attributes ){
			$attributes = $this->stringifyAttributes($this->attributes);
		}

		if( $this->innerHTML ){
			$innerHTML = (string)$this->innerHTML;
		}

		//add a space PURELY for aesthetics
		if( $attributes ){
			$attributes = " {$attributes}";
		}

		$html = sprintf($pattern, $tag, $attributes, $innerHTML);
		return str_replace("  ", " ", $html);
	}
	/**
	 * Stringify an array of attributes into properly formatted HTML attribute pairs
	 * @param array $map The array of attributes
	 * @param string $sep The glue string to use in the implode
	 * @return string
	 */
	protected function stringifyAttributes(array $map, $sep = " "){
		$pairs = array();
		foreach($map as $key => $value){
			if(ctype_digit("{$key}")){
				if(!is_scalar($value)) continue;
				$pairs[] = sprintf('%s', htmlentities($value, ENT_QUOTES, "UTF-8"));
				continue;
			}

			if(!$value) continue;

			if(is_array($value)){
				$value = implode(" ", $value);
			}

			if(is_scalar($value)){
				$pairs[] = sprintf('%s="%s"', $key, htmlentities($value, ENT_QUOTES, "UTF-8"));
			}
		}
		return ($pairs ? implode($sep, $pairs) : "");
	}


}