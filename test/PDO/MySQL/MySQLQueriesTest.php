<?php

class MySQLQueryTest extends PHPUnit_Framework_TestCase {

	public function testEqualPairsComma(){
		$sqlq   = new \Chevron\PDO\MySQL\Query;
		$method = new ReflectionMethod($sqlq, "equal_pairs");
		$method->setAccessible(true);

		$data = array("col1" => "val3", "col2" => "val4");

		$expected_comma = "`col1` = ?, `col2` = ?";
		$result = $method->invokeArgs($sqlq, array($data));
		$this->assertEquals($result, $expected_comma, "Format Error: Query::equal_pairs ... pairs (sep = ,)");
	}

	public function testEqualPairsAnd(){
		$sqlq   = new \Chevron\PDO\MySQL\Query;
		$method = new ReflectionMethod($sqlq, "equal_pairs");
		$method->setAccessible(true);

		$data = array("col1" => "val3", "col2" => "val4");

		$expected_and   = "`col1` = ? and `col2` = ?";
		$result = $method->invokeArgs($sqlq, array($data, " and "));
		$this->assertEquals($result, $expected_and, "Format Error: Query::equal_pairs ... pairs (sep = and)");
	}

	public function testMapColumns(){
		$sqlq   = new \Chevron\PDO\MySQL\Query;
		$method = new ReflectionMethod($sqlq, "map_columns");
		$method->setAccessible(true);

		$data = array("col1" => "val3", "col2" => "val4", "col3" => array(true, "NOW()"));

		$result = $method->invokeArgs($sqlq, array($data));
		list( $columns, $tokens ) = $result;

		$expected_columns = array("col1", "col2", "col3");
		$expected_tokens  = array("?", "?", "NOW()");

		$this->assertEquals($columns, $expected_columns, "Parse Error: Query::map_columns ... columns ");
		$this->assertEquals($tokens,  $expected_tokens,  "Parse Error: Query::map_columns ... tokens ");
	}

	public function testFilterData(){
		$sqlq   = new \Chevron\PDO\MySQL\Query;
		$method = new ReflectionMethod($sqlq, "filter_data");
		$method->setAccessible(true);

		$data = array("col1" => "val3", "col2" => "val4", "col3" => array(true, "NOW()"));

		$result = $method->invokeArgs($sqlq, array($data));

		$expected_values  = array("val3", "val4");

		$this->assertEquals($result, $expected_values, "Parse Error: Query::filter_data ");
	}

	public function testParenPairsSingle(){

		$sqlq   = new \Chevron\PDO\MySQL\Query;
		$method = new ReflectionMethod($sqlq, "paren_pairs");
		$method->setAccessible(true);

		$data = array(
			array("col1" => "val1", "col2" => "val2"),
			array("col1" => "val3", "col2" => "val4"),
		);

		$expected_columns = "(`col1`, `col2`)";
		$expected_tokens  = "(?, ?)";

		$result = $method->invokeArgs($sqlq, array($data, 0));
		list( $columns, $tokens ) = $result;

		$this->assertEquals($columns, $expected_columns, "Format Error: Query::paren_pairs ... columns (single)");
		$this->assertEquals($tokens,  $expected_tokens,  "Format Error: Query::paren_pairs ... tokens (single)");
	}

	public function testParenPairsMulti(){

		$sqlq   = new \Chevron\PDO\MySQL\Query;
		$method = new ReflectionMethod($sqlq, "paren_pairs");
		$method->setAccessible(true);

		$data = array(
			array("col1" => "val1", "col2" => "val2"),
			array("col1" => "val3", "col2" => "val4"),
		);

		$expected_columns = "(`col1`, `col2`)";
		$expected_tokens  = "(?, ?),(?, ?),(?, ?)";

		$result = $method->invokeArgs($sqlq, array($data, 3));
		list( $columns, $tokens ) = $result;

		$this->assertEquals($columns, $expected_columns, "Format Error: Query::paren_pairs ... columns (multi)");
		$this->assertEquals($tokens,  $expected_tokens,  "Format Error: Query::paren_pairs ... tokens (multi)");
	}

	public function testIn(){

		$sqlq = new \Chevron\PDO\MySQL\Query;

		$query = "select * from table where col1 = ? and col2 in (%s);";
		$data  = array("string", array(5, 6));

		$result = $sqlq->in($query, $data);
		list( $query, $data ) = $result;

		$expected_query = "select * from table where col1 = ? and col2 in (?, ?);";
		$expected_data  = array("string", 5, 6);

		$this->assertEquals($query, $expected_query, "Format Error: Query::in query was not formatted properly.");
		$this->assertEquals($data,  $expected_data,  "Parse Error: Query::in data was not parsed properly.");
	}

	public function testInsertSingle(){

		$sqlq = new \Chevron\PDO\MySQL\Query;

		// insert one
		$qry = $sqlq->insert("table", array("col1" => "val1", "col2" => "val2"), 0);

		$expectation = "INSERT INTO `table` (`col1`, `col2`) VALUES (?, ?);";

		$this->assertEquals($qry, $expectation, "Query::insert (single) was not formatted properly.");
	}

	public function testInsertMulti(){

		$sqlq = new \Chevron\PDO\MySQL\Query;

		// insert many
		$data = array(
			array("col1" => "val1", "col2" => "val2"),
			array("col1" => "val3", "col2" => "val4"),
		);

		$qry = $sqlq->insert("table", $data, 2);

		$expectation = "INSERT INTO `table` (`col1`, `col2`) VALUES (?, ?),(?, ?);";

		$this->assertEquals($qry, $expectation, "Query::insert (multiple) was not formatted properly.");
	}

	public function testReplaceSingle(){

		$sqlq = new \Chevron\PDO\MySQL\Query;

		// replace one
		$qry = $sqlq->replace("table", array("col1" => "val1", "col2" => "val2"), 0);

		$expectation = "REPLACE INTO `table` (`col1`, `col2`) VALUES (?, ?);";

		$this->assertEquals($qry, $expectation, "Query::replace (single) was not formatted properly.");
	}

	public function testReplaceMulti(){

		$sqlq = new \Chevron\PDO\MySQL\Query;

		// replace many
		$data = array(
			array("col1" => "val1", "col2" => "val2"),
			array("col1" => "val3", "col2" => "val4"),
		);

		$qry = $sqlq->replace("table", $data, 2);

		$expectation = "REPLACE INTO `table` (`col1`, `col2`) VALUES (?, ?),(?, ?);";

		$this->assertEquals($qry, $expectation, "Query::replace (multiple) was not formatted properly.");
	}

	public function testUpdate(){

		$sqlq = new \Chevron\PDO\MySQL\Query;

		$qry = $sqlq->update("table", array("col1" => "val1", "col2" => "val2"), array( "col3" => "val3" ));

		$expectation = "UPDATE `table` SET `col1` = ?, `col2` = ? WHERE `col3` = ?;";

		$this->assertEquals($qry, $expectation, "Query::update was not formatted properly.");
	}

	public function testOnDuplicateKey(){

		$sqlq = new \Chevron\PDO\MySQL\Query;

		$qry = $sqlq->on_duplicate_key("table", array("col1" => "val1", "col2" => "val2"), array( "col3" => "val3" ));

		$expectation = "INSERT INTO `table` SET `col1` = ?, `col2` = ?, `col3` = ? ON DUPLICATE KEY UPDATE `col1` = ?, `col2` = ?;";

		$this->assertEquals($qry, $expectation, "Query::on_duplicate_key was not formatted properly.");
	}

	public function testOnDuplicateKeyArray1(){

		$sqlq = new \Chevron\PDO\MySQL\Query;

		$qry = $sqlq->on_duplicate_key("table", array("col1" => array(true, "val1"), "col2" => "val2"), array( "col3" => "val3" ));

		$expectation = "INSERT INTO `table` SET `col1` = val1, `col2` = ?, `col3` = ? ON DUPLICATE KEY UPDATE `col1` = val1, `col2` = ?;";

		$this->assertEquals($expectation, $qry, "Query::on_duplicate_key was not formatted properly.");
	}

	public function testOnDuplicateKeyArray2(){

		$sqlq = new \Chevron\PDO\MySQL\Query;

		$data = array(
			"import_q"    => array(true, "NOW()"),
			"refresh_q"   => array(true, "NULL"),
			"last_status" => "0",
			"comments"    => "Import Queued on %s.",
			"job_name"    => "",
		);

		$where = array("myon_id" => "asdf-asdf-asdf-asdf");

		$qry = $sqlq->on_duplicate_key("table", $data, $where);

		$expectation = "INSERT INTO `table` SET `import_q` = NOW(), `refresh_q` = NULL, `last_status` = ?, `comments` = ?, `job_name` = ?, `myon_id` = ? ON DUPLICATE KEY UPDATE `import_q` = NOW(), `refresh_q` = NULL, `last_status` = ?, `comments` = ?, `job_name` = ?;";

		$this->assertEquals($expectation, $qry, "Query::on_duplicate_key was not formatted properly.");
	}

	public function testFilterData2(){
		$sqlq   = new \Chevron\PDO\MySQL\Query;
		$method = new ReflectionMethod($sqlq, "filter_data");
		$method->setAccessible(true);

		$data = array(
			"import_q"    => array(true, "NOW()"),
			"refresh_q"   => array(true, "NULL"),
			"last_status" => "0",
			"comments"    => "Import Queued on %s.",
			"job_name"    => "",
		);

		$where = array("myon_id" => "asdf-asdf-asdf-asdf");

		$result = $method->invokeArgs($sqlq, array($data, $where, $data));

		$expected_values  = array(
			"0",
			"Import Queued on %s.",
			"",
			"asdf-asdf-asdf-asdf",
			"0",
			"Import Queued on %s.",
			"",
		);

		$this->assertEquals($expected_values, $result, "Parse Error: Query::filter_data ");
	}

	public function testFilterMultiData2(){
		$sqlq   = new \Chevron\PDO\MySQL\Query;
		$method = new ReflectionMethod($sqlq, "filter_multi_data");
		$method->setAccessible(true);

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

		$result = $method->invokeArgs($sqlq, array($data));

		$expected_values  = array(
			"0",
			"Import Queued on %s.",
			"1",
			"0",
			"Import Queued on %s.",
			"2",
		);

		$this->assertEquals($expected_values, $result, "Parse Error: Query::filter_data ");
	}

}