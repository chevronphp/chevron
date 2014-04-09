<?php


function print_a( $data, $return = false ) {
	$str = "<table border=\"1\" cellpadding=\"5\" cellspacing=\"5\" width=\"500\">\n";
	$Keys = array_keys( $data );
	foreach( $Keys as $OneKey ) {
		$str .= "<tr>\n<td style=\"border:1px solid #000000;background-color:#CCCCCC;padding:5px;font-size:11px;\">";
		$str .= "<strong>" . $OneKey . "</strong></td>\n";
		$str .= "<td style=\"border:1px solid #000000;background-color:#EEEEEE;padding:5px;font-size:11px;\">";
		if ( is_array($data[$OneKey]) ){
			$str .= print_a($data[$OneKey], true);
		} else {
			$str .= $data[$OneKey];
		}
		$str .= "</td>\n</tr>\n";
	}
	$str .= "</table>\n";

	if(!$return) echo $str;
	return $str;
}
