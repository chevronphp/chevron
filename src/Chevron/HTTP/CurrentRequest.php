<?php

namespace Chevron\HTTP;

class CurrentRequest extends Request {
	/**
	 * Create a Request object based on the information in $_SERVER about the
	 * current request
	 * @param bool $request_authorization Send basic authorization headers
	 * @return Chevron\HTTP\Request
	 */
	function __construct( $request_authorization = false ){

		$auth_prefix = "";
		if($request_authorization){
			$auth_prefix = vsprintf("%s:%s@", $this->request_authorization());
		}

		$scheme = "http";
		if( array_key_exists("HTTPS", $_SERVER) && $_SERVER["HTTPS"] === 'on' ){
			$scheme = "https";
		}

		$params = array("SERVER_NAME", "SERVER_PORT", "REQUEST_URI", "REQUEST_METHOD");
		foreach($params as $param){
			if(!array_key_exists($param, $_SERVER)){
				throw new \Exception("The {$param} is missing from the server array.");
			}
		}

		$url = vsprintf("%s://%s%s:%s%s", array(
			$scheme, $auth_prefix,
			$_SERVER["SERVER_NAME"],
			$_SERVER["SERVER_PORT"],
			$_SERVER["REQUEST_URI"])
		);

		return $this->parse($url, $_SERVER["REQUEST_METHOD"]);

	}
	/**
	 * send/recieve/parse basic authorization headers
	 * @return array
	 */
	protected function request_authorization(){
		$username = $password = "";

		switch( true ){
			case( array_key_exists("PHP_AUTH_USER", $_SERVER) ):
				$username = $_SERVER['PHP_AUTH_USER'];
				$password = $_SERVER['PHP_AUTH_PW'];
			break;
			case( array_key_exists("HTTP_AUTHENTICATION", $_SERVER) ):
				if( strpos( strtolower( $_SERVER['HTTP_AUTHENTICATION'] ), 'basic' ) === 0 ) {
					list( $username, $password ) = explode( ':', base64_decode( substr( $_SERVER['HTTP_AUTHORIZATION'], 6 ) ) );
				}
			break;
			default:
				header('WWW-Authenticate: Basic realm="Chevron"');
				header('HTTP/1.0 401 Unauthorized');
				printf("It was a good rain, the kind you wait for ...");
				exit(1);
			break;
		}

		return array( $username, $password );
	}

	/**
	 * build a link for a different file in the same current requested directory
	 * @param string $file The new file
	 * @param array $params New query params
	 * @param bool $preserve Whether to append of replace the current query
	 * @return string
	 */
	function pwd($file = "", array $params = array(), $preserve = true){
		$request = clone $this;
		$path = rtrim($request->dirname, " /");
		$request->alter_request(array("path" => "{$path}/{$file}", "host" => null));
		return $request->rebuild($params, $preserve);
	}

	/**
	 * check if the current request is a POST request
	 * @return bool
	 */
	function is_post(){
		return $this->action === "POST";
	}

}