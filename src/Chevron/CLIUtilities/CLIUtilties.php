<?php

namespace Chevron\CLIUtilities;

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

function cli_indent( $str, $spaces, $length = 76 ){
	$indent = str_repeat( " ", (int)$spaces );
	return $indent . wordwrap( strtr( $str, "\r\n\t", "   " ), ( $length - $spaces ), "\n{$indent}" );
}

function cli_confirm( $msg = "" ){
	$msg = $msg ?: "Are you sure?";
	printf( "\n\n%s (y/n) ", cli_format( $msg, "reverse" ) );
	return trim( fgets( STDIN ) ) == "y";
}

function cli_choose( $msg = "" ){
	$msg = $msg ?: "Are you sure?";
	printf( "\n\n%s ", cli_format( $msg, "reverse" ) );
	return trim( fgets( STDIN ) );
}

function cli_clean_exit( $msg = "", $spaces = 0 ){
	$msg = $msg ?: "Script exited quietly.";
	$indent = str_repeat( " ", (int)$spaces );

	print( "\n\n" );
	print( str_repeat( "-", ( (int)$spaces + (int)strlen( $msg ) ) ) . "\n" );
	printf( "%s%s\n", $indent, $msg );
	print( "\n\n" );
	die();

}

function cli_man( $array ){
	if( !is_array( $array ) ) return "Please supply properly formatted documentation.";

	$final = "\n\n";
	foreach( $array as $type => $lines ){
		switch( $type ){
			case( "title" ):
				$final .= cli_format( rtrim( $lines ), "underscore" );
				$final .= "\n\n";
			break;
			case( "description" ):
				foreach( (array)$lines as $line ){ ++$n;
					$final .= cli_indent( rtrim( $line ), 0 ) . "\n\n";
				}
			break;
			case( "args" ):
				$final .= cli_format( "ARGS", "underscore" );
				$final .= "\n\n";
				foreach( (array)$lines as $arg => $arg_descr ){
					$final .= sprintf( "   %s\n%s\n\n", rtrim( $arg ), cli_indent( rtrim($arg_descr), 8 ) );
				}
				$final .= "\n\n";
			break;
		}
	}
	return $final;
}