<?php

namespace Chevron\HTML;
/**
 * a class for not having to type HTML tags by hand. includes entity safety
 * @package Chevron\HTML
 */
class Element {

	use Traits\ElementAttributeTrait;
	use Traits\ElementInnerHTMLTrait;
	use Traits\ElementPatternTrait;
	use Traits\ElementRenderTrait;
	use Traits\ElementTagTrait;

	/**
	 * Create an Element object that will stringify to an HTML tag
	 * @param string $tag The tag to create
	 * @param string $innerHTML The innerHTML of the tag
	 * @param array $attributes An arbitrary map of attrs for the element
	 * @return Chevron\HTML\Element
	 */
	function __construct( $tag, $innerHTML = "", array $attributes = [] ){
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
	 * shortcut to quickly create and return an object using the tag as the method name
	 * @param string $tag The tag name as a method call
	 * @param array $args The various args that ought to be passed to the constructor
	 * @return Element
	 */
	// static function __callStatic($tag, $args){

	// 	$innerHTML = "";
	// 	if(array_key_exists(0, $args)){
	// 		$innerHTML = $args[0];
	// 	}

	// 	$attributes = array();
	// 	if(array_key_exists(1, $args)){
	// 		if(is_array($args[1])){
	// 			$attributes = $args[1];
	// 		}
	// 	}

	// 	$CLASS = __CLASS__;
	// 	return new $CLASS($tag, $innerHTML, $attributes);

	// }
}

