<?php

namespace Chevron\Parsers;
/**
 * A wrapper class for INI parsing that offers a unified API with error
 * handling.
 */
abstract class INI {

	const INI_ERROR_NONE         = 101;
	const INI_FILE_PARSE_ERROR   = 102;
	const INI_STRING_PARSE_ERROR = 103;
	/**
	 * Offer some simple reasons as to what went wrong
	 */
	static $ini_errors = array(
		101 => "No error has occurred",
		102 => "There was an error parsing the file",
		103 => "There was an error parsing the string",
	);
	/**
	 * Parse an INI file and throw an error if it fails
	 * @param string $filename The file to parse
	 * @param bool $sections Whether or not to parse sections
	 * @return array
	 */
	static function parse_ini_file( $filename, $sections = true ){
		if( !file_exists($filename) ){
			throw new \Exception("INI file does not exist.");
		}
		$data = parse_ini_file($filename, $sections);
		if( false === $data ){
			static::throw_ini_error(static::INI_FILE_PARSE_ERROR);
		}
		return $data;
	}
	/**
	 * Parse an INI string and throw an error if it fails
	 * @param string $string The string to parse
	 * @param bool $sections Whether or not to parse sections
	 * @return array
	 */
	static function parse_ini_string( $string, $sections = true ){
		$data = parse_ini_string($string, $sections);
		if( false === $data ){
			static::throw_ini_error(static::INI_STRING_PARSE_ERROR);
		}
		return $data;
	}
	/**
	 * Throw a descriptive error if parsing an INI file/string fails
	 * @param int $error_code The error code
	 * @return
	 */
	static function throw_ini_error($error_code){
		$error = sprintf("%d: %s", $error_code, static::$ini_errors[$error_code]);
		throw new \Exception($error);
	}

}