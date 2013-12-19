<?php

if(!function_exists("ascii_cols")){
	function ascii_cols($maxWidth, $rows, $lineending = "\n"){
		$string = "";
		foreach($rows as $row){
			if(count($row) != 2) continue;

			list($left, $right) = $row;
			$chars = strlen("{$left}") + strlen("{$right}") + 2; //for spaces
			$string .= sprintf("%s %s %s%s", $left, str_repeat(".", ($maxWidth - $chars)), $right, $lineending);
		}
		return $string;
	}
}