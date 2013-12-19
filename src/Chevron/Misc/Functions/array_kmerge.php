<?php

if( !function_exists("array_kmerge") ){
	function array_kmerge(){
		$arrays = func_get_args();

		$final = array();
		foreach($arrays as $array){
			foreach($array as $key => $value){
				$final[$key] = $value;
			}
		}

		return $final;
	}
}