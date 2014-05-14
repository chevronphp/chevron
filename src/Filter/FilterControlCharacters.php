<?php

namespace Chevron\Filter;
/**
 * implements functions to strip control chars out of scalar and iteratable values
 *
 * @package Chevron\Filter
 */
class FilterControlCharacters implements Interfaces\FilterInterface {

	use Traits\FilterControlCharactersTrait;

}