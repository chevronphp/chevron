<?php

namespace Chevron\HTML;

class ElementFactory {
	/**
	 * Array of common tags that are to remain empty
	 */
	protected $emptyTags = array("param", "embed", "iframe", "script");
	/**
	 * Array of common tags that are self closing
	 */
	protected $selfClosingTags = array("hr", "br", "meta", "link", "base", "img", "input");
	/**
	 * Array of aliases to allow for input creation via type
	 */
	protected $tagAliases = array(
		"file",	"text", "search", "password", "submit", "reset", "hidden", "checkbox", "radio",
	);

	function __call($name, $args){

		$type = "";
		if( in_array($name, $this->tagAliases) ){
			$type = $name;
			$name = "input";
		}

		$el = new Element($name);

		if(is_null($el)){
			return null;
		}

		$el->setSelfClosing( in_array($el->tag, $this->selfClosingTags) );

		$el->setEmpty( in_array($el->tag, $this->emptyTags) );

		if( in_array($type, $this->tagAliases) ){
			$el->setAttributes( array( "type" => $type ) );
			return $this->assignFormAttributes($el, $args);
		}

		return $this->assignAttributes($el, $args);

	}

	protected function assignAttributes(Element $el, array $array){

		// the "innerHTML" property is the first passed arg
		if(array_key_exists(0, $array)){
			$el->setInnerHTML( (string)$array[0] );
		}

		// a generic array of attrs is passed as the second arg
		if(array_key_exists(1, $array)){
			$el->setAttributes( (array)$array[1] );
		}

		return $el;
	}

	protected function assignFormAttributes(Element $el, array $array){

		// the "name" property is the first passed arg
		if( array_key_exists(0, $array) ){
			$el->setAttributes( array("name" => $array[0]) );
		}

		// the "value" property is the second passed arg
		if( array_key_exists(1, $array) ){
			$el->setAttributes( array("value" => $array[1]) );
		}

		// the "checked" property is the third passed arg
		if( array_key_exists(2, $array) ){
			$isChecked = (bool)$array[2] ? 'checked' : null;
			$el->setAttributes( array("checked" => $isChecked) );
		}

		// a generic array of attrs is passed as the fourth arg
		if( array_key_exists(3, $array) ){
			$el->setAttributes( (array)$array[3] );
		}

		// sometimes the "value" property is also the innerHTML
		if( in_array($el->tag, array("button", "textarea")) ){
			$el->setInnerHTML($attributes["value"]);
		}

		// textareas don't have a "value" attr
		if( $el->tag == "textarea" ){
			$el->setAttributes( array("value" => null) );
		}

		// asign the "type" based on the alias
		if( array_key_exists($el->tag, $this->tagAliases) ){
			$type = $this->tagAliases[ $el->tag ]["type"];
			$el->setAttributes( array("type" => $type) );
		}

		return $el;

	}
	/**
	 * A helper function to create a select drop down form element from an array of information.
	 * This function can take a two level array of options (i.e. array($value => $display) ||
	 * array($label => array($value => $display)).
	 *
	 * @param string $name The name of the select element
	 * @param array $options The array of information with which to construct the options
	 * @param array $attributes An array of attribute value pairs to add to the select tag
	 * @param int $level An argument used for recursion to track how far drilled down the recursion is
	 * @return string
	 */
	function select($name, array $options, $selected = '', array $attributes = array(), $level = 1){
		if(!is_array($options)){ return ""; }

		$opts = $attrs = '';

		foreach($options as $key => $value){
			if(is_array($value)){
				$_options = $this->select("", $value, $selected, array(), $level + 1);
				$temp = sprintf('<optgroup label="%s">%s</optgroup>', $key, $_options);
			}else{
				$_value = $_selected = "";

				$_value = " value=\"{$key}\"";

				if(in_array($key, (array)$selected)){
					$_selected = ' selected="selected"';
				}

				$temp = sprintf('<option%s%s>%s</option>', $_value, $_selected, $value);
			}
			$opts .= $temp;
		}

		$attrs = array();

		if(!empty($name)){
			$attrs = array("name" => $name);
		}

		if(!empty($attributes)){
			$attrs = array_merge($attrs, $attributes);
		}

		if($attrs){
			// BOF -- Element::stringifyAttributes()
			$pairs = array();
			foreach($attrs as $key => $value){
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
			$attrs = ($pairs ? implode(" ", $pairs) : "");
			// EOF -- Element::stringifyAttributes()
		}

		return ($level > 1) ? $opts : sprintf('<select %s>%s</select>', $attrs, $opts);
	}

################################################################################
###########################    UNUSED FUNCTIONS    #############################
################################################################################
	/**
	 * Parse a simple CSS selector string into attributes for an HTML tag
	 * @param string $selector The selector string
	 * @return array
	 */
	protected function viaSelector($selector){
		$pattern["tag"]        = '(?:^(?P<tag>[\w]+))?';
		$pattern["id"]         = '(?:\#(?P<id>[\w\-]+))?';
		$pattern["classes"]    = '(?:\.(?P<class>[\w\-]+))?';
		$pattern["attributes"] = '(?:\[(?P<attrs>[\w\-]+)=(?P<values>.+?)\]+)?';

		$pattern = vsprintf('|%s%s%s%s|i', $pattern );

		preg_match_all($pattern, $selector, $matches);

		$tag        = array_filter($matches["tag"]);
		$id         = array_filter($matches["id"]);
		$classes    = array_filter($matches["class"]);
		$attributes = array_combine($matches["attrs"], $matches["values"]);
		$attributes = array_filter($attributes);

		if( !$tag ){
			throw new \Exception("You must supply a tag to create an element");
		}

		// merge $attributes last to ensure that they're rendered last
		// allows for potentially overwriting the ID and CLASS attributes ...
		$attrs = array_merge( array("id" => $id), array("class" => $classes), $attributes );

		return array(current($tag), $attrs);
	}
	/**
	 * Create an arrayed HTML Form name from an array
	 * @param array $name The array of names to combine
	 * @return string
	 */
	protected function arrayifyName(array $name){
		$nameSTR = str_repeat("[%s]", ( count($name) - 1 ) );
		return vsprintf("%s{$nameSTR}", $name);
		// return vsprintf("name=\"%s{$nameSTR}\"", $name);
	}

}