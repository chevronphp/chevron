<?php


return;
FUnit::test("CurrentRequest::__construct() extends BaseRequest", function(){

	$request = new \Chevron\HTTP\Requests\CurrentRequest(false);
	if(!($request InstanceOf Chevron\HTTP\Requests\BaseRequest)){
		FUnit::fail("Instantiation failed");
	}else{
		FUnit::ok(1, "Instantiation passed");
	}

});

Funit::test("CurrentRequest::info default structure", function(){
	// CurrentRequest is dependent on the $_SERVER array set in the phpunit XML manifest
	$request = new \Chevron\HTTP\Requests\CurrentRequest(false);

	$expected = array(
		"scheme"           => "http",
		"host"             => "local.chevron.com",
		"port"             => "80",
		"path"             => "/local/file/index.html",
		"query"            => "a=b&c=d",
		"sub_domain"       => "local",
		"domain"           => "chevron",
		"top_level_domain" => "com",
		"user"             => "",
		"pass"             => "",
		"query_arr"        => array(
			"a" => "b",
			"c" => "d",
		),
		"dirname"          => "/local/file",
		"basename"         => "index.html",
		"extension"        => "html",
		"filename"         => "index",
		"action"           => "GET",
	);

	foreach($expected as $key => $value){
		FUnit::equal($request->$key, $value, "value at '{$key}'");
	}
});

FUnit::test("CurrentRequest::pwd()", function(){
	$request = new \Chevron\HTTP\Requests\CurrentRequest(false);

	$original = $request->build();

	$result = $request->pwd("new_file.html");

	$url = "/local/file/new_file.html?a=b&c=d";

	Funit::equal($result, $url, "build the altered request");
	Funit::notEqual($result, $original, "change the original request");
});

FUnit::test("CurrentRequest::is_post()", function(){
	$request = new \Chevron\HTTP\Requests\CurrentRequest(false);

	$result = $request->is_post();

	FUnit::equal(false, $result);
});

