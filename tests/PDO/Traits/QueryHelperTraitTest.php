<?php

require_once("tests/bootstrap.php");

use Chevron\PDO\MySQL;

/*
 * fixtures
 */

FUnit::setup(function(){

	$dbConn = new \Chevron\PDO\MySQL\Wrapper(TEST_DB_DSN, TEST_DB_USERNAME, TEST_DB_PASSWORD);
	$dbConn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	FUnit::fixture("dbConn", $dbConn);

	$in = new ReflectionMethod($dbConn, "in");
	$in->setAccessible(true);
	FUnit::fixture("in", $in);

	$filterData = new ReflectionMethod($dbConn, "filterData");
	$filterData->setAccessible(true);
	FUnit::fixture("filterData", $filterData);

	$filterMultiData = new ReflectionMethod($dbConn, "filterMultiData");
	$filterMultiData->setAccessible(true);
	FUnit::fixture("filterMultiData", $filterMultiData);

	$parenPairs = new ReflectionMethod($dbConn, "parenPairs");
	$parenPairs->setAccessible(true);
	FUnit::fixture("parenPairs", $parenPairs);

	$equalPairs = new ReflectionMethod($dbConn, "equalPairs");
	$equalPairs->setAccessible(true);
	FUnit::fixture("equalPairs", $equalPairs);

	$mapColumns = new ReflectionMethod($dbConn, "mapColumns");
	$mapColumns->setAccessible(true);
	FUnit::fixture("mapColumns", $mapColumns);

});

FUnit::test("protected MySQL\Wrapper::in()", function(){

	$dbConn = FUnit::fixture("dbConn");

	$method = FUnit::fixture("in");

	$query = "select * from table where col1 = ? and col2 in (%s);";
	$data  = array("string", array(5, 6));

	$result = $method->invokeArgs($dbConn, array($query, $data));
	list( $query, $data ) = $result;

	$expected_query = "select * from table where col1 = ? and col2 in (?, ?);";
	$expected_data  = array("string", 5, 6);

	FUnit::equal($query, $expected_query, "query");
	FUnit::equal($data,  $expected_data,  "data");
});

FUnit::test("protected MySQL\Wrapper::equalPairs() using seperator ','", function(){

	$dbConn = FUnit::fixture("dbConn");
	$method = FUnit::fixture("equalPairs");

	$data = array("col1" => "val3", "col2" => "val4");

	$expected_comma = "`col1` = ?, `col2` = ?";
	$result = $method->invokeArgs($dbConn, array($data));
	FUnit::equal($result, $expected_comma);
});

FUnit::test("protected MySQL\Wrapper::equalPairs() using seperator 'and'", function(){

	$dbConn = FUnit::fixture("dbConn");
	$method = FUnit::fixture("equalPairs");

	$data = array("col1" => "val3", "col2" => "val4");

	$expected_and   = "`col1` = ? and `col2` = ?";
	$result = $method->invokeArgs($dbConn, array($data, " and "));
	FUnit::equal($result, $expected_and);
});

FUnit::test("protected MySQL\Wrapper::mapColumns()", function(){

	$dbConn = FUnit::fixture("dbConn");
	$method = FUnit::fixture("mapColumns");

	$data = array("col1" => "val3", "col2" => "val4", "col3" => array(true, "NOW()"));

	$result = $method->invokeArgs($dbConn, array($data));
	$columns = array_keys($result);
	$tokens  = array_values($result);

	$expected_columns = array("col1", "col2", "col3");
	$expected_tokens  = array("?", "?", "NOW()");

	FUnit::equal($columns, $expected_columns, "columns ");
	FUnit::equal($tokens,  $expected_tokens,  "tokens");
});

FUnit::test("protected MySQL\Wrapper::parenPairs()", function(){

	$dbConn = FUnit::fixture("dbConn");

	$method = FUnit::fixture("parenPairs");

	$data = array(
		array("col1" => "val1", "col2" => "val2"),
		array("col1" => "val3", "col2" => "val4"),
	);

	$expected_columns = "(`col1`, `col2`)";
	$expected_tokens  = "(?, ?)";

	$result = $method->invokeArgs($dbConn, array($data, 0));
	list( $columns, $tokens ) = $result;

	FUnit::equal($columns, $expected_columns, "columns");
	FUnit::equal($tokens,  $expected_tokens,  "tokens");
});

FUnit::test("protected MySQL\Wrapper::parenPairs() multiple pairs", function(){

	$dbConn = FUnit::fixture("dbConn");

	$method = FUnit::fixture("parenPairs");

	$data = array(
		array("col1" => "val1", "col2" => "val2"),
		array("col1" => "val3", "col2" => "val4"),
	);

	$expected_columns = "(`col1`, `col2`)";
	$expected_tokens  = "(?, ?),(?, ?),(?, ?)";

	$result = $method->invokeArgs($dbConn, array($data, 3));
	list( $columns, $tokens ) = $result;

	FUnit::equal($columns, $expected_columns, "columns");
	FUnit::equal($tokens,  $expected_tokens,  "tokens");
});

FUnit::test("protected MySQL\Wrapper::filterData()", function(){

	$dbConn = FUnit::fixture("dbConn");
	$method = FUnit::fixture("filterData");

	$data = array(
		"col1" => "val3",
		"col2" => "val4",
		"col3" => array(true, "NOW()")
	);

	$result = $method->invokeArgs($dbConn, array($data));

	$expected_values  = array("val3", "val4");

	FUnit::equal($result, $expected_values);
});

FUnit::test("protected MySQL\Wrapper::filterData() -- multiple args", function(){

	$dbConn = FUnit::fixture("dbConn");
	$method = FUnit::fixture("filterData");

	$data = array(
		"import_q"    => array(true, "NOW()"),
		"refresh_q"   => array(true, "NULL"),
		"last_status" => "0",
		"comments"    => "Import Queued on %s.",
		"job_name"    => "",
	);

	$where = array("myon_id" => "asdf-asdf-asdf-asdf");

	$result = $method->invokeArgs($dbConn, array($data, $where, $data));

	$expected_values  = array(
		"0",
		"Import Queued on %s.",
		"",
		"asdf-asdf-asdf-asdf",
		"0",
		"Import Queued on %s.",
		"",
	);

	FUnit::equal($expected_values, $result);
});

FUnit::test("protected MySQL\Wrapper::filterMultiData()", function(){

	$dbConn = FUnit::fixture("dbConn");
	$method = FUnit::fixture("filterMultiData");

	$data = array(
		array(
			"import_q"    => array(true, "NOW()"),
			"refresh_q"   => array(true, "NULL"),
			"last_status" => "0",
			"comments"    => "Import Queued on %s.",
			"job_name"    => "1",
		),
		array(
			"import_q"    => array(true, "NOW()"),
			"refresh_q"   => array(true, "NULL"),
			"last_status" => "0",
			"comments"    => "Import Queued on %s.",
			"job_name"    => "2",
		),
	);

	$result = $method->invokeArgs($dbConn, array($data));

	$expected_values  = array(
		"0",
		"Import Queued on %s.",
		"1",
		"0",
		"Import Queued on %s.",
		"2",
	);

	FUnit::equal($expected_values, $result);
});

/**
 * map_columns needs to accomodate a number of different strctures. there are
 * many test necessary to ensure that it does
 */

FUnit::test("protected MySQL\Wrapper::mapColumns() for col => val", function(){

	$dbConn = FUnit::fixture("dbConn");

	$a = array(
		"col1" => "val",
		"col2" => "val",
		"col3" => "val",
		"col4" => "val",
	);

	$columns = array(
		"col1",
		"col2",
		"col3",
		"col4",
	);

	$tokens = array(
		"?",
		"?",
		"?",
		"?",
	);

	$method = FUnit::fixture("mapColumns");

	$result  = $method->invokeArgs($dbConn, array($a));
	$c = array_keys($result);
	$t = array_values($result);

	FUnit::equal($columns, $c, "columns");
	FUnit::equal($tokens, $t, "tokens");

});

FUnit::test("protected MySQL\Wrapper::mapColumns() for col => array(true, val)", function(){

	$dbConn = FUnit::fixture("dbConn");

	$a = array(
		"col1" => array(true, "val"),
		"col2" => array(true, "val"),
		"col3" => array(true, "val"),
		"col4" => array(true, "val"),
	);

	$columns = array(
		"col1",
		"col2",
		"col3",
		"col4",
	);

	$tokens = array(
		"val",
		"val",
		"val",
		"val",
	);

	$method = FUnit::fixture("mapColumns");

	$result  = $method->invokeArgs($dbConn, array($a));
	$c = array_keys($result);
	$t = array_values($result);

	FUnit::equal($columns, $c, "columns");
	FUnit::equal($tokens, $t, "tokens");

});

FUnit::test("protected MySQL\Wrapper::mapColumns() for col => val, col => array(true, val) where arrays are last", function(){

	$dbConn = FUnit::fixture("dbConn");

	$a = array(
		"col1" => "val",
		"col2" => "val",
		"col3" => array(true, "val"),
		"col4" => array(true, "val"),
	);

	$columns = array(
		"col1",
		"col2",
		"col3",
		"col4",
	);

	$tokens = array(
		"?",
		"?",
		"val",
		"val",
	);

	$method = FUnit::fixture("mapColumns");

	$result  = $method->invokeArgs($dbConn, array($a));
	$c = array_keys($result);
	$t = array_values($result);

	FUnit::equal($columns, $c, "columns");
	FUnit::equal($tokens, $t, "tokens");

});

FUnit::test("protected MySQL\Wrapper::mapColumns() for col => val, col => array(true, val) where arrays are first", function(){

	$dbConn = FUnit::fixture("dbConn");

	$a = array(
		"col1" => array(true, "val"),
		"col2" => array(true, "val"),
		"col3" => "val",
		"col4" => "val",
	);

	$columns = array(
		"col1",
		"col2",
		"col3",
		"col4",
	);

	$tokens = array(
		"val",
		"val",
		"?",
		"?",
	);

	$method = FUnit::fixture("mapColumns");

	$result  = $method->invokeArgs($dbConn, array($a));
	$c = array_keys($result);
	$t = array_values($result);

	FUnit::equal($columns, $c, "columns");
	FUnit::equal($tokens, $t, "tokens");

});

FUnit::test("protected MySQL\Wrapper::mapColumns() for col => val, col => array(true, val) where arrays are in the middle", function(){

	$dbConn = FUnit::fixture("dbConn");

	$a = array(
		"col1" => "val",
		"col2" => array(true, "val"),
		"col3" => array(true, "val"),
		"col4" => "val",
	);

	$columns = array(
		"col1",
		"col2",
		"col3",
		"col4",
	);

	$tokens = array(
		"?",
		"val",
		"val",
		"?",
	);

	$method = FUnit::fixture("mapColumns");

	$result  = $method->invokeArgs($dbConn, array($a));
	$c = array_keys($result);
	$t = array_values($result);

	FUnit::equal($columns, $c, "columns");
	FUnit::equal($tokens, $t, "tokens");

});

FUnit::test("protected MySQL\Wrapper::mapColumns() for array(col => val)", function(){

	$dbConn = FUnit::fixture("dbConn");

	$a = array(
		array("col1" => "val"),
		array("col1" => "val"),
		array("col1" => "val"),
		array("col1" => "val"),
	);

	$columns = array(
		"col1",
	);

	$tokens = array(
		"?",
	);

	$method = FUnit::fixture("mapColumns");

	$result  = $method->invokeArgs($dbConn, array($a));
	$c = array_keys($result);
	$t = array_values($result);

	FUnit::equal($columns, $c, "columns");
	FUnit::equal($tokens, $t, "tokens");

});

FUnit::test("protected MySQL\Wrapper::mapColumns() for array(col => array(true, val))", function(){

	$dbConn = FUnit::fixture("dbConn");

	$a = array(
		array("col1" => array(true, "val")),
		array("col1" => array(true, "val")),
		array("col1" => array(true, "val")),
		array("col1" => array(true, "val")),
	);

	$columns = array(
		"col1",
	);

	$tokens = array(
		"val",
	);

	$method = FUnit::fixture("mapColumns");

	$result  = $method->invokeArgs($dbConn, array($a));
	$c = array_keys($result);
	$t = array_values($result);

	FUnit::equal($columns, $c, "columns");
	FUnit::equal($tokens, $t, "tokens");

});

FUnit::test("protected MySQL\Wrapper::mapColumns() for array(col => array(true, val), col => array(true, val))", function(){

	$dbConn = FUnit::fixture("dbConn");

	$a = array(
		array("col1" => array(true, "val"), "col2" => array(true, "val")),
		array("col1" => array(true, "val"), "col2" => array(true, "val")),
		array("col1" => array(true, "val"), "col2" => array(true, "val")),
		array("col1" => array(true, "val"), "col2" => array(true, "val")),
	);

	$columns = array(
		"col1",
		"col2",
	);

	$tokens = array(
		"val",
		"val",
	);

	$method = FUnit::fixture("mapColumns");

	$result  = $method->invokeArgs($dbConn, array($a));
	$c = array_keys($result);
	$t = array_values($result);

	FUnit::equal($columns, $c, "columns");
	FUnit::equal($tokens, $t, "tokens");

});

FUnit::test("protected MySQL\Wrapper::mapColumns() for array(col => val, col => val)", function(){

	$dbConn = FUnit::fixture("dbConn");

	$a = array(
		array("col1" => "val", "col2" => "val"),
		array("col1" => "val", "col2" => "val"),
		array("col1" => "val", "col2" => "val"),
		array("col1" => "val", "col2" => "val"),
	);

	$columns = array(
		"col1",
		"col2",
	);

	$tokens = array(
		"?",
		"?",
	);

	$method = FUnit::fixture("mapColumns");

	$result  = $method->invokeArgs($dbConn, array($a));
	$c = array_keys($result);
	$t = array_values($result);

	FUnit::equal($columns, $c, "columns");
	FUnit::equal($tokens, $t, "tokens");

});

FUnit::test("protected MySQL\Wrapper::mapColumns() for array(col => val, col => array(true, val)) where arrays are second", function(){

	$dbConn = FUnit::fixture("dbConn");

	$a = array(
		array("col1" => "val", "col2" => array(true, "val")),
		array("col1" => "val", "col2" => array(true, "val")),
		array("col1" => "val", "col2" => array(true, "val")),
		array("col1" => "val", "col2" => array(true, "val")),
	);

	$columns = array(
		"col1",
		"col2",
	);

	$tokens = array(
		"?",
		"val",
	);

	$method = FUnit::fixture("mapColumns");

	$result  = $method->invokeArgs($dbConn, array($a));
	$c = array_keys($result);
	$t = array_values($result);

	FUnit::equal($columns, $c, "columns");
	FUnit::equal($tokens, $t, "tokens");

});

FUnit::test("protected MySQL\Wrapper::mapColumns() for array(col => val, col => array(true, val)) where arrays are first", function(){

	$dbConn = FUnit::fixture("dbConn");

	$a = array(
		array("col1" => array(true, "val"), "col2" => "val"),
		array("col1" => array(true, "val"), "col2" => "val"),
		array("col1" => array(true, "val"), "col2" => "val"),
		array("col1" => array(true, "val"), "col2" => "val"),
	);

	$columns = array(
		"col1",
		"col2",
	);

	$tokens = array(
		"val",
		"?",
	);

	$method = FUnit::fixture("mapColumns");

	$result  = $method->invokeArgs($dbConn, array($a));
	$c = array_keys($result);
	$t = array_values($result);

	FUnit::equal($columns, $c, "columns");
	FUnit::equal($tokens, $t, "tokens");

});

FUnit::test("protected MySQL\Wrapper::mapColumns() for col => val with a NULL value", function(){

	$dbConn = FUnit::fixture("dbConn");

	$a = array(
		"col1" => "val",
		"col2" => null,
		"col3" => "val",
		"col4" => "val",
	);

	$columns = array(
		"col1",
		"col3",
		"col4",
	);

	$tokens = array(
		"?",
		"?",
		"?",
	);

	$method = FUnit::fixture("mapColumns");

	$result  = $method->invokeArgs($dbConn, array($a));
	$c = array_keys($result);
	$t = array_values($result);

	FUnit::equal($columns, $c, "columns");
	FUnit::equal($tokens, $t, "tokens");

});

FUnit::test("protected MySQL\Wrapper::mapColumns() for array(col => val, col => val) with a NULL value", function(){

	$dbConn = FUnit::fixture("dbConn");

	$a = array(
		array("col1" => "val", "col2" => null),
		array("col1" => "val", "col2" => null),
		array("col1" => "val", "col2" => null),
		array("col1" => "val", "col2" => null),
	);

	$columns = array(
		"col1",
	);

	$tokens = array(
		"?",
	);

	$method = FUnit::fixture("mapColumns");

	$result  = $method->invokeArgs($dbConn, array($a));
	$c = array_keys($result);
	$t = array_values($result);

	FUnit::equal($columns, $c, "columns");
	FUnit::equal($tokens, $t, "tokens");

});

