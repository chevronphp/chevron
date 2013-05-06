<?php

error_reporting(E_ALL ^ E_NOTICE);

define('IS_CLI', 0 === stripos(php_sapi_name(), "cli"));

/**
 * Define an autoloader where the application will look for a class file within
 * the new library folder
 * This function MUST be defined BEFORE the SESSION has begun
 *
 * @param $request $class The name of the file to be auto loaded
 */
spl_autoload_register(function ( $request ) {
	$sep    = DIRECTORY_SEPARATOR;
	$parts  = explode("\\", $request);

	$final = "";
	while($part = array_pop($parts)){
		$final = "{$sep}{$part}{$final}";
	}

	$final = ltrim($final, "/");
	$file = __DIR__ . "/../../{$final}.php";

	if( file_exists( $file ) ){
		include_once( $file );
	}
});

include_once( __DIR__ . "/../WeightsAndMeasures/WeightsAndMeasures.php");
include_once( __DIR__ . "/../Misc/debug_functions.php");

set_exception_handler(array('\Chevron\Errors\ExceptionHandler','handle'));

set_error_handler(array('\Chevron\Errors\ErrorHandler','handle'));