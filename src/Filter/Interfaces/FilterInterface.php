<?php

namespace Chevron\Filter\Interfaces;
/**
 * implements functions to filter scalar and iteratable values
 *
 * @package Chevron\Filter
 */
interface FilterInterface {

	/**
	 * method to take a scalar value and do something to it
	 * @param scalar $value The value to alter
	 */
	function filter($value);

	/**
	 * method to take an array and do something to each value
	 * @param array $map The array to alter
	 */
	function filterArray(array $map);

}