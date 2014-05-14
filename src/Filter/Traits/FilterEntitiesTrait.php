<?php

namespace Chevron\Filter\Traits;
/**
 * implements functions to entity-ize a scalar value
 *
 * @package Chevron\Filter
 */
trait FilterEntitiesTrait {

	/**
	 * method to entity-ize a value
	 * @param string $value The value
	 * @return string
	 */
	function filter($value){
		if(!is_scalar($value)) return;
		return htmlentities($value, ENT_QUOTES, "UTF-8");
	}

	/**
	 * method to entity-ize a value
	 * @param string $value The value
	 * @return string
	 */
	function filterArray(array $map){
		array_walk_recursive($map, function(&$value){
			$value = htmlentities($value, ENT_QUOTES, "UTF-8");
		});
		return $map;
	}
}