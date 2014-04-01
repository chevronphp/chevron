<?php

require_once("tests/bootstrap.php");

use Chevron\Container;

/**
 * test Deferred::invoke($key)
 */
FUnit::test("Deferred::invoke()", function(){

	$R = new Container\Deferred;
	$R->set("bloop", function(){ return 5; });
	$val = $R->invoke("bloop", array());
	FUnit::equal($val, 5);

});
/**
 * test Deferred::__call($key)
 */
FUnit::test("Deferred::__call()", function(){

	$R = new Container\Deferred;
	$R->set("bloop", function(){ return 5; });
	$val = $R->bloop();
	FUnit::equal($val, 5);

});
/**
 * test Deferred::invoke($key, [$args, $...])
 */
FUnit::test("Deferred::invoke() w/ arg", function(){

	$R = new Container\Deferred;
	$R->set("bloop", function($n){ return $n; });
	$in = "This is a test";
	$out = $R->invoke("bloop", array($in));
	FUnit::equal($in, $out);

});
/**
 * test Deferred::__call($key, [$args, $...])
 */
FUnit::test("Deferred::__call() w/ arg", function(){

	$R = new Container\Deferred;
	$R->set("bloop", function($n){ return $n; });
	$in = "This is a test";
	$out = $R->bloop($in);
	FUnit::equal($in, $out);

});
/**
 * test Deferred::invoke($key, [$args, $args, $...])
 */
FUnit::test("Deferred::invoke() w/ multiple args", function(){

	$R = new Container\Deferred;
	$R->set("bloop", function($a, $b){ return $a + $b; });
	$out = $R->invoke("bloop", array(3, 4));
	FUnit::equal($out, 7);

});
/**
 * test Deferred::invoke($key, [$args, $args, $...])
 */
FUnit::test("Deferred::invoke() w/ multiple mixed type args", function(){

	$R = new Container\Deferred;
	$R->set("bloop", function($a, $b){ return array($a, $b); });
	$out = $R->invoke("bloop", array("one", array("two" => "three")));
	$expected = array("one", array("two" => "three"));
	FUnit::equal($out, $expected);

});
/**
 * test Deferred::once($key [, $arg, $...])
 */
FUnit::test("Deferred::once()", function(){

	$R = new Container\Deferred;
	$R->set("bloop", function($a, $b){ return $a + $b; });
	$result = $R->once("bloop", array(3, 4));
	FUnit::equal($result, 7, "initial call");
	$result = $R->once("bloop", array(5, 6));
	FUnit::equal($result, 7, "singleton via multiple calls");

});