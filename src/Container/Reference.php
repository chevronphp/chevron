<?php

namespace Chevron\Container;
/**
 * implementation of the registry pattern that supports wrapping map via
 * reference -- for use with the $_SESSION
 *
 * @package Chevron\Container
 */
class Reference extends Registry {
	/**
	 * If an array is passed to the constructor, it is assigned by REFERENCE
	 * This is useful for a generic API to interact with the SESSION array
	 *
	 * @param array &$map The array to reference
	 * @return
	 */
	function __construct( &$map = null ) {
		if( $map === null ) {
			$map = array();
		}

		$this->map =& $map;
	}

}

