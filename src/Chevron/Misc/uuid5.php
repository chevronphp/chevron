<?php

if(!function_exists("uuid5")){
	function uuid5($n, $ns = "This is a namespace ..."){
		$ns_bytes = '';
		for ($i = 0; $i < strlen($ns); $i += 2) {
			$ns_bytes .= chr(hexdec($ns[$i] . $ns[$i+1]));
		}

		$hash = sha1($ns_bytes . $n);
		$uuid["time_low"] = substr($hash, 0, 8);
		$uuid["time_mid"] = substr($hash, 8, 4);
		$uuid["time_hi_and_version"] = ( hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000;
		$uuid["clk_seq_hi_res_variant"] = ( hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000;
		$uuid["node"] = substr($hash, 20, 12);
		return vsprintf("%08s-%04s-%04x-%04x-%12s", $uuid);
	}
}