<?php

namespace Chevron\HTML;

class Simple {

	protected $tag, $innerHTML, $attributes;

	function __construct($tag, array $attributes){
		$this->tag = $tag;

		if(array_key_exists("innerHTML", $attributes)){
			$this->innerHTML = $attributes["innerHTML"];
			unset($attributes["innerHTML"]);
		}

		$this->attributes = $attributes;
	}
	/**
	 * Properly render an Element object as an HTML tag string. The patterns
	 * omit a preceding space for the attribute strings for aesthetic reasons.
	 * @return string
	 */
	function __toString(){
		$pattern = '<%1$s%2$s>%3$s</%1$s>';

		if( in_array($this->tag, ["param", "embed", "iframe", "script"]) ){
			$pattern = '<%1$s%2$s></%1$s>';
		}

		if( in_array($this->tag, ["hr", "br", "meta", "link", "base", "img", "input"]) ){
			$pattern = '<%1$s%2$s />';
		}

		$tag = $attributes = $innerHTML = "";

		$tag = $this->tag;

		if( $this->attributes ){
			$attributes = $this->stringifyAttributes($this->attributes);
		}

		if( $this->innerHTML ){
			$innerHTML = (string)$this->innerHTML;
		}

		//add a space PURELY for aesthetics
		if( $attributes ){
			$attributes = " {$attributes}";
		}

		$html = sprintf($pattern, $tag, $attributes, $innerHTML);
		return str_replace("  ", " ", $html);
	}
	/**
	 * Stringify an array of attributes into properly formatted HTML attribute pairs
	 * @param array $map The array of attributes
	 * @param string $sep The glue string to use in the implode
	 * @return string
	 */
	protected function stringifyAttributes(array $map, $sep = " "){
		$pairs = array();
		foreach($map as $key => $value){
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
		return ($pairs ? implode($sep, $pairs) : "");
	}

}