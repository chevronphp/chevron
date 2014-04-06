<?php

namespace Chevron\Hollow;

abstract class Hollow {
	/**
	 * The map in which to store our objects
	 */
	private static $map = array();

	/**
	 * @var array The map in which we store called values
	 */
	private static $called = array();

	/**
	 * Retrieve an item; calling if callable
	 * @param string $name
	 * @param bool $new When $name is callable, force it to be called
	 * @return mixed|null
	 */
	public static function get( $name, $new = false ) {

		if(!array_key_exists($name, self::$map)){
			return null;
		}

		if(!is_callable(self::$map[$name])){
			return self::$map[$name];
		}

		if($new){
			return call_user_func(self::$map[$name]);
		}

		if(!array_key_exists($name, self::$called)){
			self::$called[$name] = call_user_func(self::$map[$name]);
		}

		return self::$called[$name];
	}

	/**
	 * Store a value via key to retrieve later
	 * @param string $name The name/key of the item
	 * @param mixed $value The value to store
	 * @return mixed
	 */
	public static function set($name, $value){
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
		if(array_key_exists($name, self::$map)){
			return self::$map[$name];
		}
		return null;
	}
}
