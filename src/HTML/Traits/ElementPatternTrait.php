<?php

namespace Chevron\HTML\Traits;

trait ElementPatternTrait {

	/**
	 * Array of common tags that are self empty
	 */
	protected $emptyTags = ["param", "embed", "iframe", "script"];

	/**
	 * Array of common tags that are self closing
	 */
	protected $selfClosingTags = ["hr", "br", "meta", "link", "base", "img", "input"];

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

}