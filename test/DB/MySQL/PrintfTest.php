<?php

class PrintfTest extends PHPUnit_Framework_TestCase {

	public function testInsertFull(){

		$dbf = new Chevron\PDO\MySQL\Printf;

		$data = array(
			"title"     => "first sprocket",
			"descr"     => "first descr",
			"type_id"   => "24",
			"updated"   => "2013-04-19 23:54:29",
		);

		// insert full
		list($query, $data) = $dbf->insert("sprockets", $data);

		$expected_query = "INSERT INTO sprockets (`title`, `descr`, `type_id`, `updated`) VALUES (?, ?, ?, ?);";
		$expected_data  = array("first sprocket", "first descr", "24", "2013-04-19 23:54:29");

		$this->assertEquals($expected_query, $query, "Printf::insert failed to properly format the query");
		$this->assertEquals($expected_data,  $data,  "Printf::insert failed to properly format the data");
	}

	public function testInsertFuncs(){

		$dbf = new Chevron\PDO\MySQL\Printf;

		$data = array(
			"title"     => "third sprocket",
			"descr"     => array(true, "UUID()"),
			"type_id"   => "26",
			"updated"   => array(true, "NOW()"),
		);

		// insert full
		list($query, $data) = $dbf->insert("sprockets", $data);

		$expected_query = "INSERT INTO sprockets (`title`, `descr`, `type_id`, `updated`) VALUES (?, UUID(), ?, NOW());";
		$expected_data  = array("third sprocket", "26");

		$this->assertEquals($expected_query, $query, "Printf::insert (funcs) failed to properly format the query");
		$this->assertEquals($expected_data,  $data,  "Printf::insert (funcs) failed to properly format the data");
	}

	public function testReplaceFull(){

		$dbf = new Chevron\PDO\MySQL\Printf;

		$data = array(
			"title"     => "third sprocket",
			"descr"     => array(true, "UUID()"),
			"type_id"   => "26",
			"updated"   => array(true, "NOW()"),
		);

		// insert full
		list($query, $data) = $dbf->replace("sprockets", $data);

		$expected_query = "REPLACE INTO sprockets (`title`, `descr`, `type_id`, `updated`) VALUES (?, UUID(), ?, NOW());";
		$expected_data  = array("third sprocket", "26");

		$this->assertEquals($expected_query, $query, "Printf::replace (funcs) failed to properly format the query");
		$this->assertEquals($expected_data,  $data,  "Printf::replace (funcs) failed to properly format the data");
	}

	public function testMultiInsert(){

		$dbf = new Chevron\PDO\MySQL\Printf;

		$data = array(
			array(
				"title"     => "multi sprocket 45",
				"descr"     => array(true, "UUID()"),
				"type_id"   => "45",
				"updated"   => array(true, "NOW()"),
			),
			array(
				"title"     => "multi sprocket 46",
				"descr"     => array(true, "UUID()"),
				"type_id"   => "46",
				"updated"   => array(true, "NOW()"),
			),
			array(
				"title"     => "multi sprocket",
				"descr"     => array(true, "UUID()"),
				"type_id"   => "47",
				"updated"   => array(true, "NOW()"),
			),
		);

		// insert full
		list($query, $data) = $dbf->multi_insert("sprockets", $data);

		$expected_query = "INSERT INTO sprockets (`title`, `descr`, `type_id`, `updated`) VALUES (?, UUID(), ?, NOW()),(?, UUID(), ?, NOW()),(?, UUID(), ?, NOW());";
		$expected_data  = array("multi sprocket 45", "45", "multi sprocket 46", "46", "multi sprocket", "47");

		$this->assertEquals($expected_query, $query, "Printf::multi_insert (funcs) failed to properly format the query");
		$this->assertEquals($expected_data,  $data,  "Printf::multi_insert (funcs) failed to properly format the data");
	}

	public function testMultiReplace(){

		$dbf = new Chevron\PDO\MySQL\Printf;

		$data = array(
			array(
				"title"     => "multi sprocket 45",
				"descr"     => array(true, "UUID()"),
				"type_id"   => "45",
				"updated"   => array(true, "NOW()"),
			),
			array(
				"title"     => "multi sprocket 46",
				"descr"     => array(true, "UUID()"),
				"type_id"   => "46",
				"updated"   => array(true, "NOW()"),
			),
			array(
				"title"     => "multi sprocket",
				"descr"     => array(true, "UUID()"),
				"type_id"   => "47",
				"updated"   => array(true, "NOW()"),
			),
		);

		// insert full
		list($query, $data) = $dbf->multi_replace("sprockets", $data);

		$expected_query = "REPLACE INTO sprockets (`title`, `descr`, `type_id`, `updated`) VALUES (?, UUID(), ?, NOW()),(?, UUID(), ?, NOW()),(?, UUID(), ?, NOW());";
		$expected_data  = array("multi sprocket 45", "45", "multi sprocket 46", "46", "multi sprocket", "47");

		$this->assertEquals($expected_query, $query, "Printf::multi_replace (funcs) failed to properly format the query");
		$this->assertEquals($expected_data,  $data,  "Printf::multi_replace (funcs) failed to properly format the data");
	}

	public function testUpdate(){

		$dbf = new Chevron\PDO\MySQL\Printf;

		$data = array(
			"title"     => "update sprocket title",
			"descr"     => "update sprocket descr",
			"type_id"   => "45",
			"updated"   => array(true, "NULL"),
		);

		$where = array(
			"sprocket_id" => "8"
		);

		// insert full
		list($query, $data) = $dbf->update("sprockets", $data, $where);

		$expected_query = "UPDATE sprockets SET `title` = ?, `descr` = ?, `type_id` = ?, `updated` = NULL WHERE `sprocket_id` = ?;";
		$expected_data  = array("update sprocket title", "update sprocket descr", "45", "8");

		$this->assertEquals($expected_query, $query, "Printf::update failed to properly format the query");
		$this->assertEquals($expected_data,  $data,  "Printf::update failed to properly format the data");
	}

	public function testUpdateOnDuplicateKey(){

		$dbf = new Chevron\PDO\MySQL\Printf;

		$data = array(
			"title"     => "update sprocket title",
			"descr"     => "update sprocket descr",
			"type_id"   => "45",
			"updated"   => array(true, "NULL"),
		);

		$where = array(
			"sprocket_id" => "8"
		);

		// insert full
		list($query, $data) = $dbf->on_duplicate_key("sprockets", $data, $where);

		$expected_query = "INSERT INTO sprockets SET `title` = ?, `descr` = ?, `type_id` = ?, `updated` = NULL, `sprocket_id` = ? ON DUPLICATE KEY UPDATE `title` = ?, `descr` = ?, `type_id` = ?, `updated` = NULL;";
		$expected_data  = array("update sprocket title", "update sprocket descr", "45", "8", "update sprocket title", "update sprocket descr", "45");

		$this->assertEquals($expected_query, $query, "Printf::on_duplicate_key failed to properly format the query");
		$this->assertEquals($expected_data,  $data,  "Printf::on_duplicate_key failed to properly format the data");
	}

}

