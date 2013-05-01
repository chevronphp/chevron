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