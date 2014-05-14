<?php

namespace Chevron\Filter;
/**
 * implements functions to strip control chars out of scalar and iteratable values
 *
 * @package Chevron\Filter
 */
class FilterEntities implements Interfaces\FilterInterface  {

	use Traits\FilterEntitiesTrait;

}