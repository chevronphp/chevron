<?php

namespace Chevron\Hollow;
/**
 * This is Hollow. A Dependency Injection Container
 *
 * Inspired by Pimple (github.com/fabpot/Pimple)
 *
 * @package Hollow
 * @author Jon Henderson
 * @license BSD 3 Clause (see below)
 */
abstract class Hollow {
	/**
	 * The map in which to store our objects
	 */
	private static $map = array();
	/**
	 * Retrieve an item; calling if callable
	 * @param string $name The name/key of the item
	 * @return mixed
	 */
	public static function get($name){
		if( array_key_exists($name, self::$map) ){
			if(!self::$map[$name]) return null;

			if(is_callable(self::$map[$name])){
				self::$map[$name] = call_user_func(self::$map[$name]);
			}

			return self::$map[$name];
		}
		return null;
	}
	/**
	 * Store a value via key to retrieve later
	 * @param string $name The name/key of the item
	 * @param mixed $value The value to store
	 * @return mixed
	 */
	public static function set($name, $value){
		// $value = is_callable($value) ? $value() : $value;
		return self::$map[$name] = $value;
	}
	/**
	 * Clone a given value into a second key
	 * @param string $src The source
	 * @param string $dest The destination
	 * @return mixed
	 */
	public static function duplicate($src, $dest){
		return self::set($dest, self::raw($src));
	}
	/**
	 * Retrieve an item without calling it
	 * @param string $name The name/key to be retrieved
	 * @return mixed
	 */
	public static function raw($name){
		$return = null;
		if(isset(self::$map[$name])){
			$return = self::$map[$name];
		}
		return $return;
	}
	/**
	 * Call a stored item, with args
	 * @param string $name The name/key of the item
	 * @param array array $args An array of args
	 * @return mixed
	 */
	// public static function call($name, array $args){
	// 	$value = self::raw($name);

	// 	if( !is_callable($value) )
	// 		throw new BadFunctionCallException(
	// 			"Value stored at '{$name}' isn't callable.\n"
	// 		);

	// 	return call_user_func_array($value, $args);
	// }
}