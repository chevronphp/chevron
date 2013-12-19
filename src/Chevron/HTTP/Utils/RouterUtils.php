<?php

namespace Chevron\HTTP\Utils;

class RouterUtils {

	const HEADER_CONTENT_TYPE = 101;
	const HEADER_STATUS_CODE  = 102;

	/**
	 * Method to generate the correct content-type header for the response
	 * @param string $type The type to retrieve
	 * @return string
	 */
	static function get_content_type($type){
		switch(trim($type, " .")){
			case "json" :
				$header = "Content-Type: application/json";
			break;
			case "xml" :
				$header = "Content-Type: text/xml";
			break;
			case "text" :
				$header = "Content-Type: text/plain";
			break;
			case "php" : break;
			case "html" :
			default :
				$header = "Content-Type: text/html";
			break;
		}
		return $header;
	}

	/**
	 * method to to generate the correct HTTP header for the response
	 * @param int $int The status code to retrieve
	 * @return string
	 */
	static function get_status_code($int){
		$code = array(
			200 => "HTTP/1.1 200 OK",
			204 => "HTTP/1.1 204 No Content",
			301 => "HTTP/1.1 301 Moved Permanently",
			302 => "HTTP/1.1 302 Temporary Redirect",
			303 => "HTTP/1.1 303 See Other",
			307 => "HTTP/1.1 307 Temporary Redirect",
			400 => "HTTP/1.1 400 Bad Request",
			401 => "HTTP/1.1 401 Unauthorized",
			403 => "HTTP/1.1 403 Forbidden",
			404 => "HTTP/1.1 404 Not Found",
			405 => "HTTP/1.1 405 Method Not Allowed",
			408 => "HTTP/1.1 408 Request Timeout",
			500 => "HTTP/1.1 500 Internal Server Error",
		);

		return $code[$int] ?: $code[404];

	}

}