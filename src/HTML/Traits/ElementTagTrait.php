<?php

namespace Chevron\HTML\Traits;

trait ElementTagTrait {

	/**
	 * the "tag" of the current object
	 */
	protected $tag;

	/**
	 * array of tags aliases
	 */
	protected $tagAliases = array(
		"file",	"text", "search", "password", "submit", "reset", "hidden", "checkbox", "radio",
	);

	/**
	 * Add content to an Element object
	 * @param scalar $innerHTML The content to add
	 * @return
	 */
	function setTag($tag){
		if( !ctype_alpha( $tag ) ){ return null; }
		$this->tag = $tag;
	}

	function getAlias($tag){
		if( $key = array_search($tag, $this->tagAliases) ){
			return $this->tagAliases[ $key ];
		}
		return false;
	}

}