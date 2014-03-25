<?php

return;
FUnit::test("RequestUtils::redirect()", function(){

	$result   = \Chevron\HTTP\Utils\RequestUtils::redirect( "/redirect/to/another/page/file.html" );
	$expected = "/redirect/to/another/page/file.html";

	FUnit::equal($expected, $result);

});

FUnit::test("RequestUtils::redirect() w/ params", function(){

	$result   = \Chevron\HTTP\Utils\RequestUtils::redirect( "/redirect/to/another/page/file.html", array("query" => "string") );
	$expected = "/redirect/to/another/page/file.html?query=string";

	FUnit::equal($expected, $result);

});

FUnit::test("RequestUtils::redirect() force SSL w/ absolute", function(){

	$result   = \Chevron\HTTP\Utils\RequestUtils::redirect( "http://local.Chevron.com/redirect/to/another/page/file.html", array(), true );
	$expected = "https://local.Chevron.com/redirect/to/another/page/file.html";

	FUnit::equal($expected, $result);

});

FUnit::test("RequestUtils::redirect() force SSL w/ relative", function(){

	$result   = \Chevron\HTTP\Utils\RequestUtils::redirect( "/redirect/to/another/page/file.html", array(), true );
	$expected = "/redirect/to/another/page/file.html";

	FUnit::equal($expected, $result);

});



