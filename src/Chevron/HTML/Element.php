<?php

namespace Chevron\HTML;

class Element {

	protected $tag, $innerHTML;
	protected $attributes = array();

	protected $empty        = array("param", "embed", "iframe", "script");
	protected $self_closing = array("hr", "br", "meta", "link", "base", "img", "input");
	/**
	 * Create an Element object that will stringify to an HTML tag
	 * @param string $selector A CSS selector string of attrs for the element
	 * @param array $map An arbitrary map of attrs for the element
	 * @return Chevron\HTML\Element
	 */
	function __construct($selector, array $map = array()){
		try {
			// don't incur the cost of the regex for a simple tag
			if( ctype_alpha($selector) ){
				$this->tag = $selector;
				$this->attrs($map);
			}else{
				list($tag, $attrs) = $this->parse_selector($selector);
				$this->tag = $tag;
				$this->attrs($attrs);
				$this->attrs($map);
			}
		} catch (\Exception $e) {
			trigger_error($e->getMessage());
		}
	}
	/**
	 * Parse a simple CSS selector string into attributes for an HTML tag
	 * @param string $selector The selector string
	 * @return array
	 */
	function parse_selector($selector){
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
	 * Add an array of attributes to an Element object after it's been instantiated
	 * @param array $map The map of attributes
	 * @return
	 */
	function attrs(array $map){
		foreach($map as $key => $value){
			if(!$value) continue;
			$this->attributes[$key] = $value;
		}
	}
	/**
	 * Add content to an Element object
	 * @param scalar $innerHTML The content to add
	 * @return
	 */
	function innerHTML($innerHTML){
		if(!is_scalar($innerHTML)){
			throw new \Exception("Element innerHTML must be scalar");
		}
		$this->innerHTML = htmlentities($innerHTML, ENT_QUOTES, "UTF-8");
	}
	/**
	 * Stringify an array of attributes into properly formatted HTML attribute pairs
	 * @param array $map The array of attributes
	 * @param string $sep The glue string to use in the implode
	 * @return string
	 */
	static function stringify_attrs(array $map, $sep = " "){
		$pairs = array();
		foreach($map as $key => $value){
			if(ctype_digit("{$key}")){
				if(!is_scalar($value)) continue;
				$pairs[] = sprintf('%s', htmlentities($value, ENT_QUOTES, "UTF-8"));
				continue;
			}

			if(is_scalar($value) || is_array($value)){
				if(!$value) continue;
				$value = implode(" ", (array)$value);
				$pairs[] = sprintf('%s="%s"', $key, htmlentities($value, ENT_QUOTES, "UTF-8"));
			}
		}
		return ($pairs ? implode($sep, $pairs) : "");
	}
	/**
	 * Properly render an Element object as an HTML tag string. The patterns
	 * omit a preceding space for the attribute strings for aesthetic reasons.
	 * @return string
	 */
	function __toString(){
		$pattern = '<%1$s%2$s>%3$s</%1$s>';

		if(in_array($this->tag, $this->empty)){
			$pattern = '<%1$s%2$s></%1$s>';
		}

		if(in_array($this->tag, $this->self_closing)){
			$pattern = '<%1$s%2$s />';
		}

		$tag = $attrs = $content = "";

		$tag = (string)$this->tag;

		if( $this->attributes ){
			$attrs = (string)static::stringify_attrs($this->attributes);
		}

		if( $this->innerHTML ){
			$content = (string)$this->innerHTML;
		}

		//add a space PURELY for aesthetics
		if( $attrs ){
			$attrs = " {$attrs}";
		}

		$html = sprintf($pattern, $tag, $attrs, $content);
		return str_replace("  ", " ", $html);
	}

}
