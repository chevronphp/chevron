<?php

namespace Chevron\HTTP\Utils;

class RequestUtils extends \Chevron\HTTP\Requests\BaseRequest {

	/**
	 * build a url, and send a location header to that URL
	 * @param string $url The base URL
	 * @param array $params The query for that URL
	 * @param bool $force_ssl If "host" isset, force an HTTPS request
	 * @return
	 */
	static function redirect( $url, array $params = array(), $force_ssl = false ){

		$self = __CLASS__;
		$request = new $self($url, $params);

		if(isset($request->scheme) && $force_ssl){
			$request->alter_request(array("scheme" => "https"));
		}

		header( sprintf( "Location: %s" , $request->build() ) );
		exit(0);

	}

}