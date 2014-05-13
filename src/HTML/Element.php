<?php

namespace Chevron\HTML;
/**
 * a class for not having to type HTML tags by hand. includes entity safety
 * @package Chevron\HTML
 */
class Element {

	use \Chevron\Filter\Traits\ToEntitiesTrait;
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
		$this->setTag($tag);

		if($innerHTML){
			$this->setInnerHTML($innerHTML);
		}

		if($attributes){
			$this->setAttributes($attributes);
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

