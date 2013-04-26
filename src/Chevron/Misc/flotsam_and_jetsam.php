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