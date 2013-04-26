<?php

namespace Chevron\Parsers;
/**
 * A wrapper class for INI parsing that offers a unified API with error
 * handling.
 */
abstract class JSON {
	/**
	 * An array of JSON parsing errors
	 */
	static $json_errors = array(
		JSON_ERROR_NONE           => "No error has occurred",
		JSON_ERROR_DEPTH          => "The maximum stack depth has been exceeded",
		JSON_ERROR_STATE_MISMATCH => "Invalid or malformed JSON",
		JSON_ERROR_CTRL_CHAR      => "Control character error, possibly incorrectly encoded",
		JSON_ERROR_SYNTAX         => "Syntax error",
		JSON_ERROR_UTF8           => "Malformed UTF-8 characters, possibly incorrectly encoded",
	);
	/**
	 * Parse a JSON file and throw an error if it fails
	 * @param string $filename The file to parse
	 * @param bool $as_array Whether to return an object or an array
	 * @return mixed
	 */
	static function parse_json_file( $filename, $as_array = true ){
		if( !file_exists($filename) ){
			throw new \Exception("JSON file does not exist.");
		}
		$string = file_get_contents($filename);
		return static::parse_json_string($string, $as_array);
	}
	/**
	 * Parse a JSON string and throw an error if it fails
	 * @param string $string The string to parse
	 * @param bool $as_array Whether to return an object or array
	 * @return mixed
	 */
	static function parse_json_string( $string, $as_array = true ){
		if( null === ($data = json_decode($string, $as_array)) ){
			static::throw_json_error();
		}
		return $data;
	}
	/**
	 * Throw a descriptive error if parsing the JSON fails
	 * @return
	 */
	static function throw_json_error(){
		$error = json_last_error();
		$error = sprintf("%d: %s", $error, static::$json_errors[$error]);
		throw new \Exception($error);
	}

}