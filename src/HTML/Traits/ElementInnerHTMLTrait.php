<?php

namespace Chevron\HTML\Traits;

trait ElementInnerHTMLTrait {

	/**
	 * the "innerHTML" of the current object
	 */
	protected $innerHTML = "";

	/**
	 * Add content to an Element object
	 * @param scalar $innerHTML The content to add
	 * @return
	 */
	function setInnerHTML($innerHTML){
		$this->innerHTML = $innerHTML;
	}

	/**
	 * method to entity-ize the innerHTML
	 * @return string
	 */
	protected function marshalInnerHTML(){
		return $this->toEntity($this->innerHTML);
	}

	/**
	 * method to sanitize values MUST exist
	 * @param scalar $value The value to convert
	 * @return string
	 */
	abstract function toEntity($value);

}