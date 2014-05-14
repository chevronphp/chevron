<?php

namespace Chevron\Filter\Traits;
/**
 * implements functions to strip control chars out of scalar and iteratable values
 *
 * @package Chevron\Filter
 */
trait FilterControlCharactersTrait {

	/**
	 * Filter a scalar value translating spaces " " for dangerous control chars.
	 * This will recurse deeper into arrays
	 *
	 * @param mixed $value The value to sanitize
	 * @return mixed
	 */
	function filter($value){
		return strtr($value, "\x00\x07\x08\x09\x0B\x0C\x0D\x1A", "\x20\x20\x20\x20\x20\x20\x20\x20");
	}

	/**
	 * Filter the values in an array, translating spaces " " for dangerous control chars.
	 * This will recurse deeper into arrays
	 *
	 * @param array $map The value to sanitize
	 * @return mixed
	 */
	function filterArray(array $map){
		array_walk_recursive($map, function(&$value){
			$value = strtr($value, "\x00\x07\x08\x09\x0B\x0C\x0D\x1A", "\x20\x20\x20\x20\x20\x20\x20\x20");
		});
		return $map;
	}
}