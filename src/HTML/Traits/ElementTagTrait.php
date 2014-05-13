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
	protected $tagAliases = [
		"file",	"text", "search", "password", "submit", "reset", "hidden", "checkbox", "radio",
	];

	/**
	 * Add content to an Element object
	 * @param scalar $innerHTML The content to add
	 * @return
	 */
	function setTag($tag){
		if( !ctype_alpha( $tag ) ){ return null; }
		$this->tag = $tag;
	}

	/**
	 * method to get the tag of an Element
	 * @return string
	 */
	function marshalTag(){
		return $this->tag;
	}

	/**
	 * method to get the tag alias (for Form objects)
	 * @param type $tag
	 * @return type
	 */
	function getAlias($tag){
		if( $key = array_search($tag, $this->tagAliases) ){
			return $this->tagAliases[ $key ];
		}
		return false;
	}

}