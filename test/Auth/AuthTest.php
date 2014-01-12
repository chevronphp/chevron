<?php

class AuthTest extends PHPUnit_Framework_TestCase {

	public function test_set_site_key(){
		$site_key = "this_is_a_site_key";
		$object = new \Chevron\Auth\Auth($site_key);

		$reflection = new ReflectionClass($object);
		$property = $reflection->getProperty("site_key");
		$property->setAccessible(true);

		$value = $property->getValue($object);

		// drop($property, $value, $object);

		$this->assertEquals($site_key, $value, "Auth::__construct failed to set the site key");

	}
	public function get_auth_obj(){
		$site_key       = "this_is_a_site_key";
		$object = new \Chevron\Auth\Auth($site_key);
		return $object;
	}

	public function test_digest_default(){

		$seed = "hash_this_string";

		$value = Chevron\Auth\Auth::digest($seed);
		$expected = "f2ed21510282a75dcf25c45e8fb83a5efe353ce6148d95c785e20a21c646fceb";

		$this->assertEquals($expected, $value, "Auth::digest failed to hash the string properly");

	}

	public function test_digest_md5(){

		$seed = "hash_this_string";

		$value = Chevron\Auth\Auth::digest($seed, "md5");
		$expected = "4b593d2b972f5a4fe0f9e9c042842869";

		$this->assertEquals($expected, $value, "Auth::digest failed to hash the string properly");

	}

	/**
	 * @depends test_set_site_key
	 */
	public function test_verify_credentials(){
		$object = $this->get_auth_obj();

		$salt           = "this_is_a_salt";
		$password       = "this_is_a_password";
		$given_password = "983975efc1e6e6a2ef06664bd60d3a561f7037d0125d67fb31383f7e6138aa18";

		$value = $object->verify_credentials($given_password, $password, $salt);

		$this->assertTrue($value, "Auth::verify_credentials failed to correclty identify a match");
	}

	/**
	 * @depends test_set_site_key
	 */
	public function test_verify_credentials_mismatch(){
		$object = $this->get_auth_obj();
		$salt           = "this_is_a_salt";
		$password       = "this_is_a_password";
		$given_password = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXaa18";

		$value = $object->verify_credentials($given_password, $password, $salt);

		$this->assertFalse($value, "Auth::verify_credentials failed to correclty identify a mis-match");
	}

	/**
	 * @depends test_set_site_key
	 */
	public function test_create_salt_and_password(){
		$object = $this->get_auth_obj();
		$salt     = "this_is_a_salt";
		$password = "this_is_a_password";

		$value = $object->create_salt_and_password($password);

		$this->assertNotEmpty($value["salt"],     "Auth::create_salt_and_password failed to generate a salt");
		$this->assertNotEmpty($value["password"], "Auth::create_salt_and_password failed to generate a password hash");

		if(!ctype_graph($value["salt"])){
			$this->fail("Auth::create_salt_and_password failed to return a salt of visible characters");
		}

		if(!ctype_graph($value["password"])){
			$this->fail("Auth::create_salt_and_password failed to return a password of visible characters");
		}

	}

	/**
	 * @depends test_set_site_key
	 */
	public function test_hash_password(){
		$object = $this->get_auth_obj();
		$salt     = "this_is_a_salt";
		$password = "this_is_a_password";

		$reflection = new ReflectionClass($object);
		$method = $reflection->getMethod('hash_password');
		$method->setAccessible(true);

		$value = $method->invoke($object, $password, $salt);
		$expected = "983975efc1e6e6a2ef06664bd60d3a561f7037d0125d67fb31383f7e6138aa18";

		$this->assertEquals($expected, $value, "Auth::hash_password failed to properly hash the password");

	}

	/**
	 * @depends test_set_site_key
	 */
	public function test_salt_passthrough(){
		$object = $this->get_auth_obj();
		$salt = "this_is_a_salt";

		$reflection = new ReflectionClass($object);
		$method = $reflection->getMethod('salt');
		$method->setAccessible(true);

		$value = $method->invoke($object, $salt);

		$this->assertEquals($salt, $value, "Auth::salt failed to return the correct salt via passthrough");

	}

	/**
	 * @depends test_set_site_key
	 */
	public function test_salt_generation(){
		$object = $this->get_auth_obj();

		$reflection = new ReflectionClass($object);
		$method = $reflection->getMethod('salt');
		$method->setAccessible(true);

		$value = $method->invoke($object);

		if(!ctype_graph($value)){
			$this->fail("Auth::salt failed to return a salt of visible characters");
		}

	}

}

