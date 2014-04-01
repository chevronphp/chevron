<?php

function get_php_error($int) {
	$array = array(
		 E_ERROR             => "E_ERROR",
		 E_WARNING           => "E_WARNING",
		 E_PARSE             => "E_PARSE",
		 E_NOTICE            => "E_NOTICE",
		 E_CORE_ERROR        => "E_CORE_ERROR",
		 E_CORE_WARNING      => "E_CORE_WARNING",
		 E_COMPILE_ERROR     => "E_COMPILE_ERROR",
		 E_COMPILE_WARNING   => "E_COMPILE_WARNING",
		 E_USER_ERROR        => "E_USER_ERROR",
		 E_USER_WARNING      => "E_USER_WARNING",
		 E_USER_NOTICE       => "E_USER_NOTICE",
		 E_STRICT            => "E_STRICT",
		 E_RECOVERABLE_ERROR => "E_RECOVERABLE_ERROR",
		 E_DEPRECATED        => "E_DEPRECATED",
		 E_USER_DEPRECATED   => "E_USER_DEPRECATED",
		 E_ALL               => "E_ALL",
	);
	return isset($array[(int) $int]) ? $array[(int) $int] : "E_UNKNOWN";
}

function cli_format( $msg, $attrs = array() ){

	$colors = array(
		"black"   => 0, "red"     => 1, "green" => 2, "yellow" => 3,
		"blue"    => 4, "magenta" => 5, "cyan"  => 6, "white"  => 7,
		"default" => 9
	);

	$attributes = array(
		"none"  => 0, "bright"  => 1, "dim"    => 2, "underscore" => 4,
		"blink" => 5, "reverse" => 7, "hidden" => 8
	);

	foreach ( (array)$attrs as $attr ){
		if ( strpos( $attr, "fg:" ) !== false ) {
			$color = substr( $attr, 3 );
			$code[] = sprintf( "3%s", $colors[ $color ] );
		} elseif ( strpos( $attr, "bg:" ) !== false ) {
			$color = substr( $attr, 3 );
			$code[] = sprintf( "4%s", $colors[ $color ] );
		} elseif ( strpos( $attr, ":" ) === false ) {
			$code[] = sprintf( "%s", $attributes[ $attr ] );
		}
	}

	$code = str_replace( ";;", ";", trim( implode( ";", $code ), "; " ) );

	$code = $code ? "\033[{$code}m%s\033[0m" : "%s";

	return sprintf( $code, $msg );

}

function cli_backtrace( $array = false ){
	global $argv;
	$debug_backtrace = $array ?: debug_backtrace();
	$debug_backtrace = array_slice( $debug_backtrace, 2 );

	$order = array( "file", "line", "class", "object", "type", "function", "args" );
	$indent = sprintf( "\n%18s", " " );

	$final = "";
    foreach( $debug_backtrace as $row ){
    	$args  = "";
    	$file = ltrim(str_replace( getcwd(), "", $row["file"] ), '/');
    	$final .= sprintf( "\n%s:%s\n", $file, $row["line"] );
    	$final .= sprintf( "%'-76s\n\n", "-" );
    	$final .= sprintf( "%12s => %s\n", "file",     $file );
    	$final .= sprintf( "%12s => %s\n", "line",     $row["line"] );
    	if( !empty($row["class"]) )  { $final .= sprintf( "%12s => %s\n", "class",    $row["class"] ); }
    	if( !empty($row["object"]) ) { $final .= sprintf( "%12s => %s\n", "object",   ( is_object( $row["object"] ) ? get_class( $row["object"] ) : "" ) ); }
    	if( !empty($row["type"]) )   { $final .= sprintf( "%12s => %s\n", "type",     $row["type"] ); }
    	$final .= sprintf( "%12s => %s\n", "function", $row["function"] );
    	if( is_array( $row["args"] ) ){
			foreach( $row["args"] as $i => $_arg ){
				$arg   = str_replace( "\n", $indent, print_r( $_arg, true ) );
				$args .= $i == 0 ? sprintf( "- %s\n", $arg ) : sprintf( "%16s- %s\n", " ", $arg );
			}
    	}
    	$final .= sprintf( "%12s => %s\n\n", "args", $args );
	}

    return sprintf( "%s\n\n" , $final);
}

/**
 * Testing the odd/even value of an int.
 * You'll inevitably need to alternate colors on rows ...
 *
 * @param int $num Tests $num in a bitwise fashion to see if it's value is an odd number
 * @example $style = ((base::is_odd($num)) ? 'odd' : 'even');
 * @return bool
 */
function is_odd($num){
	return (bool)($num & 1);
}


/*
 * output a formatted string of hours and minutes for a given seconds value
 *
 * @author Nick Bartlett
 * @date Apr 20, 2011
 * @param int $seconds
 * @return
 */
function seconds_to_HM($seconds){

	$hours   = floor($seconds / 3600);
	$minutes = floor(($seconds % 3600) / 60);

	$str = '';
	if ($hours) $str .= sprintf("%s hr%s, ", number_format($hours), ($hours == 1 ? '' : 's'));
	$str .= sprintf("%d min", $minutes);

	return $str;
}

/**
 * Function to change a number of seconds to a display of HH:MM:SS
 *
 * @param mixed $seconds The string or int value to change
 * @return string
 */
function seconds_to_HMS($seconds){

	$hours   = floor($seconds / 3600);
	$minutes = floor(($seconds % 3600) / 60);
	$seconds = floor($seconds % 60);

	return sprintf("%s:%02d:%02d", number_format($hours), $minutes, $seconds);
}

function duration( $start, $end ){

	$seconds = (int)$end - (int)$start;

	$mins = ($seconds / 60);
	if(!is_int($mins)){
		$mins = floor($mins);
		$secs = ($seconds % 60);
	}


	if($mins > 60){
		$hours = ($mins / 60);
		if(!is_int($hours)){
			$hours = floor($hours);
			$mins = $mins % 60;
		}
	}

	return sprintf("%02s:%02s:%02s", $hours, $mins, $secs);

}

/***/
function index2assoc( $array ){
	while( $key = array_shift( $array ) ){
		$final[ $key ] = array_shift( $array );
	}
	return $final;
}



/**
 * Round a number to the nearest 5, as per metametrics
 */
function round_to_nearest_5($int){
	if(empty($int)){
		return $int;
	}
	return ((int)((int)$int / 5) * 5);
}


/**
 * Method to encapsulate a dictionary of HTTP errors
 * @param int $int The int of the error code
 * @param bool $message A toggle to return the explanation
 * @return string
 */
function HTTP_error_code( $int, $explanation = false ){

	$error_codes = array(
		"200" => array(
				"message" => "",
				"code" => "HTTP/1.1 200 OK",
				"explanation" => "Standard response for successful HTTP requests. The actual response will depend on the request method used. In a GET request, the response will contain an entity corresponding to the requested resource. In a POST request the response will contain an entity describing or containing the result of the action."
			),
		"204" => array(
				"message" => "",
				"code" => "HTTP/1.1 204 No Content",
				"explanation" => "The server successfully processed the request, but is not returning any content."
			),
		"301" => array(
				"message" => "",
				"code" => "HTTP/1.1 301 Moved Permanently",
				"explanation" => "This and all future requests should be directed to the given URI."
			),
		"303" => array(
				"message" => "",
				"code" => "HTTP/1.1 303 See Other",
				"explanation" => "The response to the request can be found under another URI using a GET method. When received in response to a PUT, it should be assumed that the server has received the data and the redirect should be issued with a separate GET message."
			),
		"307" => array(
				"message" => "",
				"code" => "HTTP/1.1 307 Temporary Redirect",
				"explanation" => "In this occasion, the request should be repeated with another URI, but future requests can still use the original URI. In contrast to 303, the request method should not be changed when reissuing the original request. For instance, a POST request must be repeated using another POST request."
			),
		"400" => array(
				"message" => "",
				"code" => "HTTP/1.1 400 Bad Request",
				"explanation" => "The request contains bad syntax or cannot be fulfilled."
			),
		"401" => array(
				"message" => "",
				"code" => "HTTP/1.1 401 Unauthorized",
				"explanation" => "Similar to 403 Forbidden, but specifically for use when authentication is possible but has failed or not yet been provided. The response must include a WWW-Authenticate header field containing a challenge applicable to the requested resource. See Basic access authentication and Digest access authentication."
			),
		"403" => array(
				"message" => "",
				"code" => "HTTP/1.1 403 Forbidden",
				"explanation" => "The request was a legal request, but the server is refusing to respond to it. Unlike a 401 Unauthorized response, authenticating will make no difference."
			),
		"404" => array(
				"message" => "Interesting how you know how to work a computer but you can't find a simple webpage ... ",
				"code" => "HTTP/1.1 404 Not Found",
				"explanation" => "The requested resource could not be found but may be available again in the future. Subsequent requests by the client are permissible."
			),
		"405" => array(
				"message" => "",
				"code" => "HTTP/1.1 405 Method Not Allowed",
				"explanation" => ":: A request was made of a resource using a request method not supported by that resource; for example, using GET on a form which requires data to be presented via POST, or using PUT on a read-only resource."
			),
		"408" => array(
				"message" => "",
				"code" => "HTTP/1.1 408 Request Timeout",
				"explanation" => ":: The server timed out waiting for the request. The client did not produce a request within the time that the server was prepared to wait. The client MAY repeat the request without modifications at any later time."
			),
		"500" => array(
				"message" => "",
				"code" => "HTTP/1.1 500 Internal Server Error",
				"explanation" => "A generic error message, given when no more specific message is suitable."
			)
	);

	if( $error_codes[ $int ] ){
		return $explanation ? $error_codes[ $int ]["explanation"] : $error_codes[ $int ]["code"];
	}

	return "";

}

function time_display($seconds){
		$times = array(86400 => "day", 3600 => "hour", 60 => "minute");
		foreach($times as $duration => $label){
			$exp = $seconds / $duration;
			if( $exp >= 1 ){
				return sprintf("%s %s(s)", round($exp, 2), $label);
			}
		}
	}