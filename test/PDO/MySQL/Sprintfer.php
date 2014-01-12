<?php

class SprintferTest extends PHPUnit_Framework_TestCase {

	protected $dbConn;

	public function __construct(){
		if(null == $this->dbConn){
			$pdo = new Chevron\PDO\MySQL\Sprintfer( TEST_DB_DSN, TEST_DB_USERNAME, TEST_DB_PASSWORD );
			$this->dbConn = $pdo;
		}
		return $this->dbConn;
	}

	public function testInsert(){

		$value = $this->dbConn->insert("table", array("col" => "as'df", "col2" => "qw\"er"));
		$expected = "INSERT INTO `table` (`col`, `col2`) VALUES ('as\'df', 'qw\\\"er');";

		$this->assertEquals($expected, $value, "Sprintfer::insert FAILED!!!");

	}

	public function testUpdate(){

		$value = $this->dbConn->update("table", array("col" => "as'df", "col2" => "qw\"er"), array("id" => 13));
		$expected = "UPDATE `table` SET `col` = 'as\'df', `col2` = 'qw\\\"er' WHERE `id` = '13';";

		$this->assertEquals($expected, $value, "Sprintfer::update FAILED!!!");

	}

	public function testReplace(){

		$value = $this->dbConn->replace("table", array("col" => "as'df", "col2" => "qw\"er"));
		$expected = "REPLACE INTO `table` (`col`, `col2`) VALUES ('as\'df', 'qw\\\"er');";

		$this->assertEquals($expected, $value, "Sprintfer::replace FAILED!!!");

	}

	public function testMultiReplace(){

		$value = $this->dbConn->multi_replace("table", array(array("col" => "as'df", "col2" => "qw\"er"), array("col" => "zxcv", "col2" => "mnbv")), true);
		$expected = "REPLACE INTO `table` (`col`, `col2`) VALUES ('as\'df', 'qw\\\"er'),('zxcv', 'mnbv');";

		$this->assertEquals($expected, $value, "Sprintfer::multi_replace FAILED!!!");

	}

	public function testMultiInsert(){

		$value = $this->dbConn->multi_insert("table", array(array("col" => "as'df", "col2" => "qw\"er"), array("col" => "zxcv", "col2" => "mnbv")), true);
		$expected = "INSERT INTO `table` (`col`, `col2`) VALUES ('as\'df', 'qw\\\"er'),('zxcv', 'mnbv');";

		$this->assertEquals($expected, $value, "Sprintfer::multi_insert FAILED!!!");

	}

	public function testOnDuplicateKey(){

		$value = $this->dbConn->on_duplicate_key("table", array("col" => "as'df", "col2" => "qw\"er"), array("id" => 13));
		$expected = "INSERT INTO `table` SET `col` = 'as\'df', `col2` = 'qw\\\"er', `id` = '13' ON DUPLICATE KEY UPDATE `col` = 'as\'df', `col2` = 'qw\\\"er';";

		$this->assertEquals($expected, $value, "Sprintfer::on_duplicate_key FAILED!!!");

	}

}