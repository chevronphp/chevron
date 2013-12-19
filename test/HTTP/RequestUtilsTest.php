<?php

class RequestUtilsTest extends PHPUnit_Framework_TestCase {

	public function test_redirect(){

		$result   = \Chevron\HTTP\Utils\RequestUtils::redirect( "/redirect/to/another/page/file.html" );
		$expected = "/redirect/to/another/page/file.html";

		$this->assertEquals($expected, $result, "RequestUtils::redirect failed to return the proper URL");

	}

	public function test_redirect_params(){

		$result   = \Chevron\HTTP\Utils\RequestUtils::redirect( "/redirect/to/another/page/file.html", array("query" => "string") );
		$expected = "/redirect/to/another/page/file.html?query=string";

		$this->assertEquals($expected, $result, "RequestUtils::redirect failed to return the proper URL");

	}

	public function test_redirect_force_ssl(){

		$result   = \Chevron\HTTP\Utils\RequestUtils::redirect( "http://local.Chevron.com/redirect/to/another/page/file.html", array(), true );
		$expected = "https://local.Chevron.com/redirect/to/another/page/file.html";

		$this->assertEquals($expected, $result, "RequestUtils::redirect failed to return the proper URL");

	}

	public function test_redirect_force_ssl_relative(){

		$result   = \Chevron\HTTP\Utils\RequestUtils::redirect( "/redirect/to/another/page/file.html", array(), true );
		$expected = "/redirect/to/another/page/file.html";

		$this->assertEquals($expected, $result, "RequestUtils::redirect failed to return the proper URL");

	}

}