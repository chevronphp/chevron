<?php

namespace Chevron\HTML\Traits;

trait ElementRenderTrait {

	/**
	 * Properly render an Element object as an HTML tag string. The patterns
	 * omit a preceding space for the attribute strings for aesthetic reasons.
	 * @return string
	 */
	abstract function __toString();

	/**
	 * method to explicitly call __toString();
	 * @return string
	 */
	function render(){
		return $this->__toString();
	}

}