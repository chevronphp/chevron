<?php

if( !function_exists("array_flatten_unique") ){
	function array_flatten_unique( ) {
		$iter1 = new RecursiveArrayIterator(func_get_args());
		$iter2 = new RecursiveIteratorIterator($iter1);

		$final = array();
		foreach($iter2 as $key => $value){
			$final[$key] = $value;
		}

		return $final;
	}
}