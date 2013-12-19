<?php

namespace Chevron\Filter;

abstract class Basic {
	/**
	 * Filter a mixed value translating spaces " " for dangerous control chars.
	 * This will recurse deeper into arrays
	 *
	 * @param array $map The value to sanitize
	 * @return mixed
	 */
	public static function sanitize_array(array $map){
		array_walk_recursive($map, function(&$value){
			$value = strtr($value, "\x00\x07\x08\x09\x0B\x0C\x0D\x1A", "\x20\x20\x20\x20\x20\x20\x20\x20");
		});

		return $map;
	}
	/**
	 * Filter a mixed value translating spaces " " for dangerous control chars.
	 * This will recurse deeper into arrays
	 *
	 * @param mixed $value The value to sanitize
	 * @return mixed
	 */
	public static function sanitize_scalar($value){
		return strtr($value, "\x00\x07\x08\x09\x0B\x0C\x0D\x1A", "\x20\x20\x20\x20\x20\x20\x20\x20");
	}

}
