<?php

namespace Chevron\HTTP\Utils;

/**
 * a class to specify a consistent way to pass information between routers
 * and layouts
 */
class Fulfillment {

	const HEADER_CONTENT_TYPE = 101;
	const HEADER_STATUS_CODE  = 102;

	protected $headers;
	protected $layout;
	protected $error;

	function setHeader($key, $value){
		$this->headers[$key] = $value;
	}

	function setLayout($layout){
		$this->layout = $layout;
	}

	function setError(callable $error){
		$this->error = $error;
	}

	function __get($name){
		if(property_exists($this, $name)){
			return $this->$name;
		}
		return null;
	}

	/**
	 * Method to generate the correct content-type header for the response
	 * @param string $type The type to retrieve
	 * @return string
	 */
	function setContentType($type){
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
		$this->setHeader(static::HEADER_CONTENT_TYPE, $header);
	}

	/**
	 * method to to generate the correct HTTP header for the response
	 * @param int $int The status code to retrieve
	 * @return string
	 */
	function setStatusCode($int){
		$headers = array(
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

		$header = $headers[$int] ?: $headers[404];
		$this->setHeader(static::HEADER_STATUS_CODE, $header);
	}

}