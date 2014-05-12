<?php

namespace Chevron\HTML\Traits;

trait ElementRenderTrait {

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
	 * method to explicitly call __toString();
	 * @return string
	 */
	function render(){
		return $this->__toString();
	}

}