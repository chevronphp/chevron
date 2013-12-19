<?php

class SQLiteQueryTest extends PHPUnit_Framework_TestCase {

	public function testReplaceSingle(){

		$sqlq = new \Chevron\PDO\SQLite\Query;

		// replace one
		$qry = $sqlq->replace("table", array("col1" => "val1", "col2" => "val2"), 0);

		$expectation = "INSERT OR REPLACE INTO table (`col1`, `col2`) VALUES (?, ?);";

		$this->assertEquals($qry, $expectation, "Query::replace (single) was not formatted properly.");
	}

	public function testReplaceMulti(){

		$sqlq = new \Chevron\PDO\SQLite\Query;

		// replace many
		$data = array(
			array("col1" => "val1", "col2" => "val2"),
			array("col1" => "val3", "col2" => "val4"),
		);

		$qry = $sqlq->replace("table", $data, 2);

		$expectation = "INSERT OR REPLACE INTO table (`col1`, `col2`) VALUES (?, ?),(?, ?);";

		$this->assertEquals($qry, $expectation, "Query::replace (multiple) was not formatted properly.");
	}

}