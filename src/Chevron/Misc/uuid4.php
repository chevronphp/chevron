<?php

if(function_exists("uuid4")){
	function uuid4($len = 32, $charset = ""){
		$str = ''; $i = 0;
		while( $i <= $len && ++$i ){
			$str .= chr( mt_rand(33, 126) );
		}

		$hash = sha1( gmdate("Y, d M H:i:s") . mt_rand(99, 99999999) . $str );
		$uuid["time_low"] = substr($hash, 0, 8);
		$uuid["time_mid"] = substr($hash, 8, 4);
		$uuid["time_hi_and_version"] = ( hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x4000;
		$uuid["clk_seq_hi_res_variant"] = ( hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000;
		$uuid["node"] = substr($hash, 20, 12);
		return vsprintf("%08s-%04s-%04x-%04x-%12s", $uuid);
	}
}