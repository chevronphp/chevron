<?php

/**
 * Define an autoloader where the application will look for a class file within
 * the new library folder
 * This function MUST be defined BEFORE the SESSION has begun
 *
 * @param $request $class The name of the file to be auto loaded
 */
spl_autoload_register(function ( $request ) {
	$file = str_replace("\\", DIRECTORY_SEPARATOR, $request);
	$path = dirname(dirname(__DIR__));
	if( file_exists( "{$path}/{$file}.php" ) ){
		include_once( "{$path}/{$file}.php" );
	}
});