<?php

// FUnit::setup(function(){
	FUnit::fixture("defaultInfo", array(
		"scheme"           => "",
		"host"             => "",
		"port"             => "",
		"path"             => "",
		"query"            => "",
		"sub_domain"       => "",
		"domain"           => "",
		"top_level_domain" => "",
		"user"             => "",
		"pass"             => "",
		"query_arr"        => array(),
		"dirname"          => "",
		"basename"         => "",
		"extension"        => "",
		"filename"         => "",
		"action"           => "",
		"hash"             => "",
		"status_code"      => "200",
		"content_type"     => "",
	));

	FUnit::fixture("absReq", function(){
		$request = new \Chevron\HTTP\Requests\BaseRequest;
		$request->parse("http://local.testing.com/dir/file.html?qry=str&snow=white");
		return $request;
	});

	FUnit::fixture("relReq", function(){
		$request = new \Chevron\HTTP\Requests\BaseRequest;
		$request->parse("/dir/file.html?qry=str&snow=white");
		return $request;
	});

// });

FUnit::test("BaseRequest::__construct() returns correct instance", function(){
	$request = new \Chevron\HTTP\Requests\BaseRequest;
	if(!($request InstanceOf \Chevron\HTTP\Requests\BaseRequest)){
		FUnit::fail("Instantiation failed");
	}else{
		FUnit::ok(1, "Instantiation passed");
	}
});

FUnit::test("BaseRequest::__get()", function(){
	$request = new \Chevron\HTTP\Requests\BaseRequest;
	$default = FUnit::fixture("defaultInfo");
	foreach($default as $key => $value){
		FUnit::equal($request->$key, $value, "value at '{$key}'");
	}
});

FUnit::test("BaseRequest::__construct() w/ a relative url`", function(){
	$url = "/dir/file.html?qry=str&snow=white";

	$request = new \Chevron\HTTP\Requests\BaseRequest($url);

	$expected = array(
		"path"      => "/dir/file.html",
		"query"     => "qry=str&snow=white",
		"query_arr" => array(
			"qry"   => "str",
			"snow"  => "white"
		),
		"dirname"   => "/dir",
		"basename"  => "file.html",
		"extension" => "html",
		"filename"  => "file",
		"action"    => "GET",
		"hash"      => "a4ddefcdc97fdb01c26af90e6329701f"
	);

	$default = FUnit::fixture("defaultInfo");
	$expected = array_merge($default, $expected);

	foreach($expected as $key => $value){
		FUnit::equal($request->$key, $value, "value at '{$key}'");
	}
});

FUnit::test("BaseRequest::__construct() w/ a absolute url", function(){
	$url = "http://local.testing.com/dir/file.html?qry=str&snow=white";

	$request = new \Chevron\HTTP\Requests\BaseRequest($url);

	$expected = array(
		"scheme"           => "http",
		"host"             => "local.testing.com",
		"path"             => "/dir/file.html",
		"query"            => "qry=str&snow=white",
		"sub_domain"       => "local",
		"domain"           => "testing",
		"top_level_domain" => "com",
		"query_arr"        => array(
			"qry"   => "str",
			"snow"  => "white"
		),
		"dirname"          => "/dir",
		"basename"         => "file.html",
		"extension"        => "html",
		"filename"         => "file",
		"action"           => "GET",
		"hash"             => "a4ddefcdc97fdb01c26af90e6329701f"
	);

	$default = FUnit::fixture("defaultInfo");
	$expected = array_merge($default, $expected);

	foreach($expected as $key => $value){
		FUnit::equal($request->$key, $value, "value at '{$key}'");
	}
});

FUnit::test("BaseRequest::__construct() w/ a absolute url & additional params", function(){
	$url = "http://local.testing.com/dir/file.html?qry=str&snow=white";

	$additional = array(
		"seven" => "little people"
	);

	$request = new \Chevron\HTTP\Requests\BaseRequest($url, $additional);

	$expected = array(
		"scheme"           => "http",
		"host"             => "local.testing.com",
		"path"             => "/dir/file.html",
		"query"            => "seven=little+people",
		"sub_domain"       => "local",
		"domain"           => "testing",
		"top_level_domain" => "com",
		"query_arr"        => array("seven" => "little people"),
		"dirname"          => "/dir",
		"basename"         => "file.html",
		"extension"        => "html",
		"filename"         => "file",
		"action"           => "GET",
		"hash"             => "a4ddefcdc97fdb01c26af90e6329701f"
	);

	$default = FUnit::fixture("defaultInfo");
	$expected = array_merge($default, $expected);

	foreach($expected as $key => $value){
		FUnit::equal($request->$key, $value, "value at '{$key}'");
	}
});

FUnit::test("BaseRequest::parse_extended()", function(){
	$request = new \Chevron\HTTP\Requests\BaseRequest;

	$info = array(
		"host"  => "local.chevron.com",
		"query" => "mobile=phones",
		"path"  => "/path/to/file.html",
	);

	$result = $request->parse_extended($info);

	$expected = array(
		"host"             => "local.chevron.com",
		"top_level_domain" => "com",
		"domain"           => "chevron",
		"sub_domain"       => "local",
		"query"            => "mobile=phones",
		"query_arr"        => array("mobile"=>"phones"),
		"path"             => "/path/to/file.html",
		"dirname"          => "/path/to",
		"basename"         => "file.html",
		"filename"         => "file",
		"extension"        => "html",
		'hash'             => '68527be74e41edaf65030fba85e9011d'
	);

	foreach($expected as $key => $value){
		FUnit::equal($result[$key], $value, "value at '{$key}'");
	}

});

FUnit::test("BaseRequest::parse_extended() w/ nulls", function(){
	$request = new \Chevron\HTTP\Requests\BaseRequest;

	$info = array(
		"host"  => null,
		"query" => "mobile=phones",
		"path"  => "/path/to/file.html",
	);

	$result = $request->parse_extended($info);

	$expected = array(
		"query"            => "mobile=phones",
		"query_arr"        => array("mobile"=>"phones"),
		"path"             => "/path/to/file.html",
		"dirname"          => "/path/to",
		"basename"         => "file.html",
		"filename"         => "file",
		"extension"        => "html",
		'hash'             => '68527be74e41edaf65030fba85e9011d'
	);

	foreach($expected as $key => $value){
		FUnit::equal($result[$key], $value, "value at '{$key}'");
	}

});

FUnit::test("BaseRequest::parse() w/ absolute url", function(){
	$url = "http://local.testing.com/dir/file.html?qry=str&snow=white";

	$request = new \Chevron\HTTP\Requests\BaseRequest;
	$result  = $request->parse($url);

	$expected = array(
		"scheme"           => "http",
		"host"             => "local.testing.com",
		"path"             => "/dir/file.html",
		"query"            => "qry=str&snow=white",
		"sub_domain"       => "local",
		"domain"           => "testing",
		"top_level_domain" => "com",
		"query_arr"        => array(
			"qry"=>"str",
			"snow"=>"white"
		),
		"dirname"   => "/dir",
		"basename"  => "file.html",
		"extension" => "html",
		"filename"  => "file",
		"action"    => "GET",
		"hash"      => "a4ddefcdc97fdb01c26af90e6329701f"
	);

	$default = FUnit::fixture("defaultInfo");
	$expected = array_merge($default, $expected);

	foreach($expected as $key => $value){
		FUnit::equal($request->$key, $value, "value at '{$key}'");
	}
});

FUnit::test("BaseRequest::parse() w/ absolute url w/o query", function(){
	$url = "http://local.testing.com/dir/file.html";

	$request = new \Chevron\HTTP\Requests\BaseRequest;
	$result  = $request->parse($url);

	$expected = array(
		"scheme"           => "http",
		"host"             => "local.testing.com",
		"path"             => "/dir/file.html",
		"sub_domain"       => "local",
		"domain"           => "testing",
		"top_level_domain" => "com",
		"dirname"          => "/dir",
		"basename"         => "file.html",
		"extension"        => "html",
		"filename"         => "file",
		"action"           => "GET",
		"hash"             => "a4ddefcdc97fdb01c26af90e6329701f"
	);

	$default = FUnit::fixture("defaultInfo");
	$expected = array_merge($default, $expected);

	foreach($expected as $key => $value){
		FUnit::equal($request->$key, $value, "value at '{$key}'");
	}
});

FUnit::test("BaseRequest::parse() w/ relative url", function(){
	$url = "/dir/file.html?q=s&t=f";

	$request = new \Chevron\HTTP\Requests\BaseRequest;
	$result  = $request->parse($url);

	$expected = array(
		"path"      => "/dir/file.html",
		"query"     => "q=s&t=f",
		"query_arr" => array(
			"q" => "s",
			"t" => "f",
		),
		"dirname"   => "/dir",
		"basename"  => "file.html",
		"extension" => "html",
		"filename"  => "file",
		"action"    => "GET",
		"hash"      => "a4ddefcdc97fdb01c26af90e6329701f"
	);

	$default = FUnit::fixture("defaultInfo");
	$expected = array_merge($default, $expected);

	foreach($expected as $key => $value){
		FUnit::equal($request->$key, $value, "value at '{$key}'");
	}
});

FUnit::test("BaseRequest::parse() w/ relative url w/o query", function(){
	$url = "/dir/file.html";

	$request = new \Chevron\HTTP\Requests\BaseRequest;
	$result  = $request->parse($url);

	$expected = array(
		"path"      => "/dir/file.html",
		"dirname"   => "/dir",
		"basename"  => "file.html",
		"extension" => "html",
		"filename"  => "file",
		"action"    => "GET",
		"hash"      => "a4ddefcdc97fdb01c26af90e6329701f"
	);

	$default = FUnit::fixture("defaultInfo");
	$expected = array_merge($default, $expected);

	foreach($expected as $key => $value){
		FUnit::equal($request->$key, $value, "value at '{$key}'");
	}
});

FUnit::test("BaseRequest::build() w/ absolute url", function(){
	$url = FUnit::fixture("absReq");

	$result   = $url()->build();
	$expected = "http://local.testing.com/dir/file.html?qry=str&snow=white";

	FUnit::equal($expected, $result);
});

FUnit::test("BaseRequest::build() w/ relative url", function(){
	$url = FUnit::fixture("relReq");

	$result   = $url()->build();
	$expected = "/dir/file.html?qry=str&snow=white";

	FUnit::equal($expected, $result);
});

FUnit::test("BaseRequest::alter_request() w/ relative url", function(){
	$request = FUnit::fixture("relReq");
	$request = $request();

	$changes = array(
		"host" => "chevron.com",
		"port" => "8080",
	);

	$request->alter_request($changes);

	$expected = array(
		"host"             => "chevron.com",
		"port"             => "8080",
		"path"             => "/dir/file.html",
		"query"            => "qry=str&snow=white",
		"domain"           => "chevron",
		"top_level_domain" => "com",
		"query_arr"        => array(
			"qry" => "str",
			"snow" => "white",
		),
		"dirname"          => "/dir",
		"basename"         => "file.html",
		"extension"        => "html",
		"filename"         => "file",
		"action"           => "GET",
		"hash"             => "a4ddefcdc97fdb01c26af90e6329701f"
	);

	$default = FUnit::fixture("defaultInfo");
	$expected = array_merge($default, $expected);

	foreach($expected as $key => $value){
		FUnit::equal($request->$key, $value, "value at '{$key}'");
	}
});

FUnit::test("BaseRequest::build() after alter_request", function(){
	$request = FUnit::fixture("relReq");
	$request = $request();

	$changes = array(
		"host" => "chevron.com",
		"port" => "8080",
	);

	$result = $request->alter_request($changes);
	$result = $request->build();

	$expected = "http://chevron.com:8080/dir/file.html?qry=str&snow=white";

	FUnit::equal($expected, $result);
});

FUnit::test("BaseRequest::build() after alter_request w/ auth headers", function(){
	$request = FUnit::fixture("relReq");
	$request = $request();

	$changes = array(
		"host"   => "chevron.com",
		"port"   => "8080",
		"user"   => "goose",
		"pass"   => "dog",
	);

	$result = $request->alter_request($changes);
	$result = $request->build();

	$expected = "http://goose:dog@chevron.com:8080/dir/file.html?qry=str&snow=white";

	FUnit::equal($expected, $result);
});

FUnit::test("BaseRequest::alter_query() preserving query", function(){
	$request = FUnit::fixture("relReq");
	$request = $request();

	$changes = array(
		"host"   => "chevron.com",
		"port"   => "8080",
		"user"   => "goose",
		"pass"   => "dog",
		"spaces" => "goose is a dog",
	);

	$request->alter_query($changes);

	$expected = array(
		"path"             => "/dir/file.html",
		"query"            => "qry=str&snow=white&host=chevron.com&port=8080&user=goose&pass=dog&spaces=goose+is+a+dog",
		"query_arr"        => array(
			"qry"    => "str",
			"snow"   => "white",
			"host"   => "chevron.com",
			"port"   => "8080",
			"user"   => "goose",
			"pass"   => "dog",
			"spaces" => "goose is a dog",
		),
		"dirname"          => "/dir",
		"basename"         => "file.html",
		"extension"        => "html",
		"filename"         => "file",
		"action"           => "GET",
		"hash"             => "a4ddefcdc97fdb01c26af90e6329701f"
	);

	$default = FUnit::fixture("defaultInfo");
	$expected = array_merge($default, $expected);

	foreach($expected as $key => $value){
		FUnit::equal($request->$key, $value, "value at '{$key}'");
	}
});

FUnit::test("BaseRequest::alter_query() w/o preserving query", function(){
	$request = FUnit::fixture("relReq");
	$request = $request();

	$changes = array(
		"host"   => "chevron.com",
		"port"   => "8080",
		"user"   => "goose",
		"pass"   => "dog",
		"spaces" => "goose is a dog",
	);

	$request->alter_query($changes, false);

	$expected = array(
		"path"             => "/dir/file.html",
		"query"            => "host=chevron.com&port=8080&user=goose&pass=dog&spaces=goose+is+a+dog",
		"query_arr"        => array(
			"host"   => "chevron.com",
			"port"   => "8080",
			"user"   => "goose",
			"pass"   => "dog",
			"spaces" => "goose is a dog",
		),
		"dirname"          => "/dir",
		"basename"         => "file.html",
		"extension"        => "html",
		"filename"         => "file",
		"action"           => "GET",
		"hash"             => "a4ddefcdc97fdb01c26af90e6329701f"
	);

	$default = FUnit::fixture("defaultInfo");
	$expected = array_merge($default, $expected);

	foreach($expected as $key => $value){
		FUnit::equal($request->$key, $value, "value at '{$key}'");
	}
});

FUnit::test("BaseRequest::rebuild() w/ absolute", function(){
	$request = FUnit::fixture("absReq");
	$request = $request();

	$changes = array(
		"host"   => "chevron.com",
		"port"   => "8080",
		"user"   => "goose",
		"pass"   => "dog",
		"spaces" => "goose is a dog",
	);

	$result = $request->rebuild($changes, false);

	$expected = "http://local.testing.com/dir/file.html?host=chevron.com&port=8080&user=goose&pass=dog&spaces=goose+is+a+dog";

	FUnit::equal($expected, $result);
});

FUnit::test("BaseRequest::__isset()", function(){
	$request = FUnit::fixture("absReq");
	$request = $request();

	$user    = isset($request->user);
	$pass    = isset($request->pass);
	$host    = isset($request->host);
	$dirname = isset($request->dirname);

	FUnit::equal(false, $user, "not set");
	FUnit::equal(false, $pass, "not set");
	FUnit::equal(true, $host, "set");
	FUnit::equal(true, $dirname, "set");
});
