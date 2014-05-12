<?php

namespace;

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