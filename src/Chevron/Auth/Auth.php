<?php

namespace Chevron\Auth;

class Auth {

	protected $site_key;

	function __construct($site_key){
		$this->site_key = $site_key;
	}

	function verify_credentials($given_password, $password, $salt){
		return $given_password === $this->hash_password($password, $salt);
	}

	function create_salt_and_password($password){
		$salt = $this->salt();
		return array(
			"salt"     => $salt,
			"password" => $this->hash_password( $password, $salt ),
		);
	}

	protected function hash_password($password, $salt = false) {
		return self::digest( $this->site_key . $password . $this->salt( $salt ) );
	}

	protected function salt($salt = false) {
		if( strlen( $salt ) ) { return $salt; }
		return substr(uniqid(rand()).str_shuffle("!@#$%^&*()_+=-';:,<.>0124679aBEeFJjzZYksSlIipMRtwGo"),0,64);
	}

	static function digest( $str, $algo = "sha256" ){
		return hash( $algo, $str );
	}

}