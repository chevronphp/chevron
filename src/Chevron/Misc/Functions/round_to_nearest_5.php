<?php

if(!function_exists("round_to_nearest_5")){
	function round_to_nearest_5($int){
		if(empty($int)){
			return $int;
		}
		return ((int)((int)$int / 5) * 5);
	}
}