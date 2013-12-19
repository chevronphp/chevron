<?php

if( !function_exists("between") ){
	function between( $value, $min, $max ) {
		if( ((int)$value > (int)$min) && ((int)$value < (int)$max) ){
			return true;
		}
		return false;
	}
}
