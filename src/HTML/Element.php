<?php

namespace Chevron\HTML;
/**
 * a class for not having to type HTML tags by hand. includes entity safety
 * @package Chevron\HTML
 */
class Element {
	/**
	 * the "tag" of the current object
	 */
	protected $tag;

	/**
	 * the "innerHTML" of the current object
	 */
	protected $innerHTML = "";

	/**
	 * the "attributes" of the current object
	 */
	protected $attributes = array();

	/**
	 * Array of common tags that are self empty
	 */
	protected $emptyTags = array("param", "embed", "iframe", "script");

	/**
	 * Array of common tags that are self closing
	 */
	protected $selfClosingTags = array("hr", "br", "meta", "link", "base", "img", "input");

	/**
	 * Create an Element object that will stringify to an HTML tag
	 * @param string $tag The tag to create
	 * @param string $innerHTML The innerHTML of the tag
	 * @param array $attributes An arbitrary map of attrs for the element
	 * @return Chevron\HTML\Element
	 */
	function __construct( $tag, $innerHTML, $attributes ){
		if( !ctype_alpha( $tag ) ){ return null; }
		$this->tag = $tag;

		if($innerHTML){
			$this->setInnerHTML($innerHTML);
		}

		if($attributes){
			$this->setAttributes($attributes);
		}
	}

	/**
	 * Add content to an Element object
	 * @param scalar $innerHTML The content to add
	 * @return
	 */
	function setInnerHTML($innerHTML){
		$this->innerHTML = $innerHTML;
	}

	/**
	 * Add an array of attributes to an Element object after it's been instantiated
	 * @param array $map The map of attributes
	 * @return
	 */
	function setAttributes(array $map){
		foreach($map as $key => $value){
			$this->attributes[$key] = $value;
		}
	}

	/**
	 * Properly render an Element object as an HTML tag string. The patterns
	 * omit a preceding space for the attribute strings for aesthetic reasons.
	 * @return string
	 */
	function __toString(){

		return vsprintf($this->getPattern(), array(
			$this->tag,
			$this->marshalAttributes(),
			$this->marshalInnerHTML()
		));

	}

	/**
	 * method to determine if the current tag is empty
	 * @return bool
	 */
	protected function isEmptyTag(){
		return in_array($this->tag, $this->emptyTags);
	}

	/**
	 * method to determine if the current tag is self closing
	 * @return bool
	 */
	protected function isSelfClosingTag(){
		return in_array($this->tag, $this->selfClosingTags);
	}

	/**
	 * method to return the correct printf pattern
	 * @return string
	 */
	protected function getPattern(){

		$pattern = '<%1$s%2$s>%3$s</%1$s>';

		if( $this->isEmptyTag() ){
			$pattern = '<%1$s%2$s></%1$s>';
		}

		if( $this->isSelfClosingTag() ){
			$pattern = '<%1$s%2$s />';
		}

		return $pattern;
	}

	/**
	 * method to convert the current attributes to an entitiy-ized string
	 * @return string
	 */
	protected function marshalAttributes(){
		ksort($this->attributes); // if we alphabetize, it's easier to test

		$pairs = " ";
		foreach($this->attributes as $key => $value){
			if(is_null($value)){ continue; }

			if(ctype_digit("{$key}")){
				$pairs .= $this->toEntity($value) . " ";
				continue;
			}

			if(is_array($value)){
				$value = implode(" ", $value);
			}

			if(is_scalar($value)){
				$pairs .= sprintf('%s="%s" ', $key, $this->toEntity($value));
			}
		}
		return rtrim($pairs);
	}

	/**
	 * method to entity-ize the innerHTML
	 * @return string
	 */
	protected function marshalInnerHTML(){
		return $this->toEntity($this->innerHTML);
	}

	/**
	 * method to entity-ize a value
	 * @param string $value The value
	 * @return string
	 */
	protected function toEntity($value){
		if(!is_scalar($value)) return;
		return htmlentities($value, ENT_QUOTES, "UTF-8");
	}

	/**
	 * shortcut to quickly create and return an object using the tag as the method name
	 * @param string $tag The tag name as a method call
	 * @param array $args The various args that ought to be passed to the constructor
	 * @return Element
	 */
	static function __callStatic($tag, $args){

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

		$CLASS = __CLASS__;
		return new $CLASS($tag, $innerHTML, $attributes);

	}
	/**
	 * method to explicitly call __toString();
	 * @return string
	 */
	function render(){
		return $this->__toString();
	}
}

