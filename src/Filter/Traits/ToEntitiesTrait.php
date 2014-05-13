<?php

namespace Chevron\Filter\Traits;
/**
 * implements functions to entity-ize a scalar value
 *
 * @package Chevron\Filter
 */
trait ToEntitiesTrait {

	/**
	 * method to entity-ize a value
	 * @param string $value The value
	 * @return string
	 */
	protected function toEntity($value){
		if(!is_scalar($value)) return;
		return htmlentities($value, ENT_QUOTES, "UTF-8");
	}
}