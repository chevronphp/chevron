<?php

require_once("tests/bootstrap.php");

use Chevron\PDO\Mock;

$dbConn = new Mock\Wrapper;

FUnit::test("Mock\Wrapper::put()", function()use($dbConn){

	$expected = 8675309;
	$dbConn->next($expected);
	$result = $dbConn->put("", array(), array());
	FUnit::equal($expected, $result);

});

FUnit::test("Mock\Wrapper::insert()", function()use($dbConn){

	$expected = 8675309;
	$dbConn->next($expected);
	$result = $dbConn->insert("", array(), array());
	FUnit::equal($expected, $result);

});

FUnit::test("Mock\Wrapper::update()", function()use($dbConn){

	$expected = 8675309;
	$dbConn->next($expected);
	$result = $dbConn->update("", array(), array());
	FUnit::equal($expected, $result);

});

FUnit::test("Mock\Wrapper::replace()", function()use($dbConn){

	$expected = 8675309;
	$dbConn->next($expected);
	$result = $dbConn->replace("", array(), array());
	FUnit::equal($expected, $result);

});

FUnit::test("Mock\Wrapper::on_duplicate_key()", function()use($dbConn){

	$expected = 8675309;
	$dbConn->next($expected);
	$result = $dbConn->on_duplicate_key("", array(), array());
	FUnit::equal($expected, $result);

});

FUnit::test("Mock\Wrapper::multi_insert()", function()use($dbConn){

	$expected = 8675309;
	$dbConn->next($expected);
	$result = $dbConn->multi_insert("", array(), array());
	FUnit::equal($expected, $result);

});

FUnit::test("Mock\Wrapper::multi_replace()", function()use($dbConn){

	$expected = 8675309;
	$dbConn->next($expected);
	$result = $dbConn->multi_replace("", array(), array());
	FUnit::equal($expected, $result);

});

FUnit::test("Mock\Wrapper::exe()", function()use($dbConn){

	$expected = 8675309;
	$dbConn->next($expected);
	$result = $dbConn->exe("", array(), true);
	FUnit::equal($expected, $result);

});

FUnit::test("Mock\Wrapper::assoc()", function()use($dbConn){

	$expected = 8675309;
	$dbConn->next($expected);
	$result = $dbConn->assoc("", array(), true);
	FUnit::equal($expected, $result);

});

FUnit::test("Mock\Wrapper::row()", function()use($dbConn){

	$expected = 8675309;
	$dbConn->next($expected);
	$result = $dbConn->row("", array(), true);
	FUnit::equal($expected, $result);

});

FUnit::test("Mock\Wrapper::scalar()", function()use($dbConn){

	$expected = 8675309;
	$dbConn->next($expected);
	$result = $dbConn->scalar("", array(), true);
	FUnit::equal($expected, $result);

});

FUnit::test("Mock\Wrapper::scalars()", function()use($dbConn){

	$expected = 8675309;
	$dbConn->next($expected);
	$result = $dbConn->scalars("", array(), true);
	FUnit::equal($expected, $result);

});

FUnit::test("Mock\Wrapper::keypair()", function()use($dbConn){

	$expected = 8675309;
	$dbConn->next($expected);
	$result = $dbConn->keypair("", array(), true);
	FUnit::equal($expected, $result);

});

FUnit::test("Mock\Wrapper::keyrow()", function()use($dbConn){

	$expected = 8675309;
	$dbConn->next($expected);
	$result = $dbConn->keyrow("", array(), true);
	FUnit::equal($expected, $result);

});
