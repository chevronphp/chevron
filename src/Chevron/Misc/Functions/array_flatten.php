<?php

if( !function_exists("array_flatten") ){
	function array_flatten( ) {
		$iter1 = new RecursiveArrayIterator(func_get_args());
		$iter2 = new RecursiveIteratorIterator($iter1);

		$final = array();
		foreach($iter2 as $key => $value){
			$final[] = $value;
		}

		return $final;
	}
}
