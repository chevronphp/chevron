<?php

class WrapperTest extends PHPUnit_Framework_TestCase {

	protected $dbConn;

	public function __construct(){
		if(null == $this->dbConn){
			$pdo = new Chevron\PDO\MySQL\Wrapper( TEST_DB_DSN, TEST_DB_USERNAME, TEST_DB_PASSWORD );
			$this->dbConn = $pdo;
		}
		return $this->dbConn;
	}

	public function setUp(){
		$this->setUpWidgets();
		$this->setUpSprockets(false);
	}

	public function setUpWidgets(){

		$drop_table = "DROP TABLE IF EXISTS `widgets`;";

		$create_table = "
			CREATE TABLE `widgets` (
			  `title` varchar(255) DEFAULT NULL,
			  `widget_id` int(11) NOT NULL AUTO_INCREMENT,
			  `descr` text,
			  `type_id` int(11) DEFAULT NULL,
			  `updated` datetime DEFAULT NULL,
			  PRIMARY KEY (`widget_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";

		$populate_table = "
			INSERT INTO `widgets` VALUES
			 ('5397182fed3869547139e31bcd36e536',1,'627d1a84-a976-11e2-910d-973c674f56c2',38,'2013-04-19 23:54:29'),
			 ('59937e546341411e72996003bb97bcd1',2,'627d1bba-a976-11e2-910d-973c674f56c2',39,'2013-04-19 23:54:29'),
			 ('155e5d75b8461058ce49f26f9a6e8440',3,'627d1c00-a976-11e2-910d-973c674f56c2',40,'2013-04-19 23:54:29'),
			 ('ec9cdeaa47e8dfa4c593029a842407f5',4,'627d1c46-a976-11e2-910d-973c674f56c2',41,'2013-04-19 23:54:29'),
			 ('3ab3b1eaa3cd8049b89f4133fa54c531',5,'627d1c82-a976-11e2-910d-973c674f56c2',42,'2013-04-19 23:54:29'),
			 ('bbd68671b9b3841a3b43a9a018708c13',6,'627d1cb4-a976-11e2-910d-973c674f56c2',43,'2013-04-19 23:54:29'),
			 ('cfdd54ded8c9d81729569f37cf01fdcf',7,'627d1d2c-a976-11e2-910d-973c674f56c2',44,'2013-04-19 23:54:29'),
			 ('815ff2889752897f120037010b36f921',8,'627d1d68-a976-11e2-910d-973c674f56c2',45,'2013-04-19 23:54:29'),
			 ('245344a9c9fdcd00a97482e2b42efd59',9,'627d1da4-a976-11e2-910d-973c674f56c2',46,'2013-04-19 23:54:29'),
			 ('962e9985f90fe6e1d438e042487c0725',10,'627d1dd6-a976-11e2-910d-973c674f56c2',47,'2013-04-19 23:54:29');
		";

		$qry = $this->dbConn->exec($drop_table);
		$qry = $this->dbConn->exec($create_table);
		$qry = $this->dbConn->exec($populate_table);

	}

	public function setUpSprockets($insert){

		$drop_table = "DROP TABLE IF EXISTS `sprockets`;";

		$create_table = "
			CREATE TABLE `sprockets` (
			  `title` varchar(255) DEFAULT NULL,
			  `sprocket_id` int(11) NOT NULL AUTO_INCREMENT,
			  `descr` text,
			  `type_id` int(11) DEFAULT NULL,
			  `updated` datetime DEFAULT NULL,
			  PRIMARY KEY (`sprocket_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";

		$populate_table = "
			INSERT INTO `sprockets` VALUES
			('2a067a09feb7ff9469b28a2da8633326',1,'6eb54e2a-a949-11e2-910d-973c674f56c2',24,'2013-04-19 23:50:10'),
			('f44d6c28dd0ccd5aab7d8ac6f3779317',2,'6eb55118-a949-11e2-910d-973c674f56c2',25,'2013-04-19 23:50:10'),
			('bfcf5c5605bfac4771339ba506fe010a',3,'6eb55302-a949-11e2-910d-973c674f56c2',26,'2013-04-19 23:50:10'),
			('6b28d86415f1dca50408d68729870606',4,'6eb5542e-a949-11e2-910d-973c674f56c2',27,'2013-04-19 23:50:10'),
			('d6813682ef256981af0804131c6e511b',5,'6eb55550-a949-11e2-910d-973c674f56c2',28,'2013-04-19 23:50:10'),
			('3f0ea5d32694d6e9e5e48b151f2a110e',6,'6eb55668-a949-11e2-910d-973c674f56c2',29,'2013-04-19 23:50:10'),
			('91a865b2a9203759adc67cc782f106f3',7,'6eb55780-a949-11e2-910d-973c674f56c2',30,'2013-04-19 23:50:10'),
			('c07b9092ab18cdf2d7f1b16c45aa661a',8,'6eb558a2-a949-11e2-910d-973c674f56c2',31,'2013-04-19 23:50:10'),
			('5c47eb59a87972b52ef54d28468af150',9,'6eb559b0-a949-11e2-910d-973c674f56c2',32,'2013-04-19 23:50:10'),
			('991e4d03df889f9b6c30a1730a1643ae',10,'6eb55ac8-a949-11e2-910d-973c674f56c2',33,'2013-04-19 23:50:10');
		";

		$qry = $this->dbConn->exec($drop_table);
		$qry = $this->dbConn->exec($create_table);

		// only add data when replacing and updating
		if($insert){
			$qry = $this->dbConn->exec($populate_table);
		}

	}

	public function testPassthru(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$expected = "ec9cdeaa47e8dfa4c593029a842407f5";

		$sql = "select title from widgets where widget_id = ? order by widget_id;";
		$qry = $this->dbConn->prepare($sql);
		$qry->execute(array(4));
		$result = $qry->fetch(PDO::FETCH_ASSOC);
		$this->assertEquals($result["title"], $expected, "The DB test didn't run.");
	}

	public function testScalar(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$expected = "ec9cdeaa47e8dfa4c593029a842407f5";

		$sql = "select title from widgets where widget_id = ? order by widget_id;";

		// standard
		$result = $this->dbConn->scalar($sql, array(4));
		$this->assertInternalType("scalar", $result, "Wrapper::scalar did not return scalar");
		$this->assertEquals($result, $expected, "Wrapper::scalar returned the wrong result");
	}

	public function testScalarIn(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$expected = "ec9cdeaa47e8dfa4c593029a842407f5";

		$sql = "select title from widgets where widget_id in (%s) order by widget_id;";

		// using IN
		$result = $this->dbConn->scalar($sql, array(array(4,5,6)), true);
		$this->assertInternalType("scalar", $result, "Wrapper::scalar (in) did not return scalar");
		$this->assertEquals($result, $expected, "Wrapper::scalar (in) returned the wrong result");

	}

	public function testScalarEmptyResult(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$sql = "select title from widgets where widget_id in (%s) order by widget_id;";

		// empty
		$result = $this->dbConn->scalar($sql, array(array(14,15,16)), true);
		$this->assertInternalType("null", $result, "Wrapper::scalar (empty result) did not return scalar");
		$this->assertEquals($result, null, "Wrapper::scalar returned (empty result) the wrong result");
	}

	public function testScalars(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$expected = array(2,3);

		$sql = "select widget_id from widgets where widget_id in (?, ?) order by widget_id;";

		// standard
		$result = $this->dbConn->scalars($sql, array(2,3));
		$this->assertInternalType("array", $result, "Wrapper::scalars did not return array");
		$this->assertEquals($result, $expected, "Wrapper::scalars returned the wrong result");

	}

	public function testScalarsIn(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$expected = array(2,3);

		$sql = "select widget_id from widgets where widget_id in (%s) order by widget_id;";

		// using IN
		$result = $this->dbConn->scalars($sql, array(array(2,3)), true);
		$this->assertInternalType("array", $result, "Wrapper::scalars (in) did not return array");
		$this->assertEquals($result, $expected, "Wrapper::scalars (in) returned the wrong result");

	}

	public function testScalarsEmptyResult(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$sql = "select widget_id from widgets where widget_id in (%s) order by widget_id;";

		// empty
		$result = $this->dbConn->scalars($sql, array(array(12,13)), true);
		$this->assertInternalType("array", $result, "Wrapper::scalars (empty result) did not return array");
		$this->assertEquals($result, array(), "Wrapper::scalars (empty result) returned the wrong result");
	}

	public function testKeyPair(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$expected = array(2 => 39, 3 => 40);

		$sql = "select widget_id, type_id from widgets where widget_id in (?, ?) order by widget_id;";

		// standard
		$result = $this->dbConn->keypair($sql, array(2,3));
		$this->assertInternalType("array", $result, "Wrapper::keypair did not return array");
		$this->assertEquals($result, $expected, "Wrapper::keypair returned the wrong result");

	}

	public function testKeyPairIn(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$expected = array(2 => 39, 3 => 40);

		$sql = "select widget_id, type_id from widgets where widget_id in (%s) order by widget_id;";

		// using IN
		$result = $this->dbConn->keypair($sql, array(array(2,3)), true);
		$this->assertInternalType("array", $result, "Wrapper::keypair (in) did not return array");
		$this->assertEquals($result, $expected, "Wrapper::keypair (in) returned the wrong result");

	}

	public function testKeyPairEmptyResult(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$sql = "select widget_id, type_id from widgets where widget_id in (%s) order by widget_id;";

		// empty
		$result = $this->dbConn->keypair($sql, array(array(12,13)), true);
		$this->assertInternalType("array", $result, "Wrapper::keypair (empty result) did not return array");
		$this->assertEquals($result, array(), "Wrapper::keypair (empty result) returned the wrong result");
	}

	public function testRow(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$expected = array(
			0           => "5397182fed3869547139e31bcd36e536",
			"title"     => "5397182fed3869547139e31bcd36e536",
			1           => "1",
			"widget_id" => "1",
			2           => "627d1a84-a976-11e2-910d-973c674f56c2",
			"descr"     => "627d1a84-a976-11e2-910d-973c674f56c2",
			3           => "38",
			"type_id"   => "38",
			4           => "2013-04-19 23:54:29",
			"updated"   => "2013-04-19 23:54:29",
		);

		$sql = "select * from widgets where widget_id = ? order by widget_id;";

		// standard
		$result = $this->dbConn->row($sql, array(1));
		$this->assertInternalType("array", $result, "Wrapper::row did not return array");
		$this->assertEquals($result, $expected, "Wrapper::row returned the wrong result");

	}

	public function testRowIn(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$expected = array(
			0           => "5397182fed3869547139e31bcd36e536",
			"title"     => "5397182fed3869547139e31bcd36e536",
			1           => "1",
			"widget_id" => "1",
			2           => "627d1a84-a976-11e2-910d-973c674f56c2",
			"descr"     => "627d1a84-a976-11e2-910d-973c674f56c2",
			3           => "38",
			"type_id"   => "38",
			4           => "2013-04-19 23:54:29",
			"updated"   => "2013-04-19 23:54:29",
		);

		$sql = "select * from widgets where widget_id in (%s) order by widget_id;";

		// using IN
		$result = $this->dbConn->row($sql, array(array(1,2,3)), true);
		$this->assertInternalType("array", $result, "Wrapper::row (in) did not return array");
		$this->assertEquals($result, $expected, "Wrapper::row (in) returned the wrong result");

	}

	public function testRowEmptyResult(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$sql = "select * from widgets where widget_id in (%s) order by widget_id;";

		// empty
		$result = $this->dbConn->row($sql, array(array(11,12,13)), true);
		$this->assertInternalType("array", $result, "Wrapper::row (empty result) did not return array");
		$this->assertEquals($result, array(), "Wrapper::row (empty result) returned the wrong result");

	}

	public function testAssoc(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$expected = array(
			array(
				"title"     => "59937e546341411e72996003bb97bcd1",
				"widget_id" => "2",
				"descr"     => "627d1bba-a976-11e2-910d-973c674f56c2",
				"type_id"   => "39",
				"updated"   => "2013-04-19 23:54:29",
			),
			array(
				"title"     => "155e5d75b8461058ce49f26f9a6e8440",
				"widget_id" => "3",
				"descr"     => "627d1c00-a976-11e2-910d-973c674f56c2",
				"type_id"   => "40",
				"updated"   => "2013-04-19 23:54:29",
			)
		);

		$sql = "select * from widgets where widget_id in (?, ?) order by widget_id;";

		// standard
		$result = $this->dbConn->assoc($sql, array(2,3));
		$this->assertInternalType("array", $result, "Wrapper::assoc did not return array");
		$this->assertEquals($result, $expected, "Wrapper::assoc returned the wrong result");

	}

	public function testAssocIn(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$expected = array(
			array(
				"title"     => "59937e546341411e72996003bb97bcd1",
				"widget_id" => "2",
				"descr"     => "627d1bba-a976-11e2-910d-973c674f56c2",
				"type_id"   => "39",
				"updated"   => "2013-04-19 23:54:29",
			),
			array(
				"title"     => "155e5d75b8461058ce49f26f9a6e8440",
				"widget_id" => "3",
				"descr"     => "627d1c00-a976-11e2-910d-973c674f56c2",
				"type_id"   => "40",
				"updated"   => "2013-04-19 23:54:29",
			)
		);

		$sql = "select * from widgets where widget_id in (%s) order by widget_id;";

		// using IN
		$result = $this->dbConn->assoc($sql, array(array(2,3)), true);
		$this->assertInternalType("array", $result, "Wrapper::assoc (in) did not return array");
		$this->assertEquals($result, $expected, "Wrapper::assoc (in) returned the wrong result");

	}

	public function testAssocEmptyResult(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$sql = "select * from widgets where widget_id in (%s) order by widget_id;";

		// empty
		$result = $this->dbConn->assoc($sql, array(array(12,13)), true);
		$this->assertInternalType("array", $result, "Wrapper::assoc (empty result) did not return array");
		$this->assertEquals($result, array(), "Wrapper::assoc (empty result) returned the wrong result");
	}

	public function testExeWithOutResult(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$expected = true;

		$sql = "delete from widgets where widget_id in (?, ?)";

		// standard
		$result = $this->dbConn->exe($sql, array(2,3));
		$this->assertInternalType("bool", $result, "Wrapper::exe did not return bool");
		$this->assertEquals($result, $expected, "Wrapper::exe the wrong result");

		$this->setUp();
	}

	public function testExeWithOutResultIn(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$expected = true;

		$sql = "delete from widgets where widget_id in (%s)";

		// using IN
		$result = $this->dbConn->exe($sql, array(array(4,5)), true);
		$this->assertInternalType("bool", $result, "Wrapper::exe (in) did not return bool");
		$this->assertEquals($result, $expected, "Wrapper::exe (in) the wrong result");

		$this->setUp();
	}

	/**
	 * @depends testScalars
	 */
	public function testInsertFull(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$this->setUpSprockets(false);

		$data = array(
			"title"     => "first sprocket",
			"descr"     => "first descr",
			"type_id"   => "24",
			"updated"   => "2013-04-19 23:54:29",
		);

		// insert full
		$result = $this->dbConn->insert("sprockets", $data);
		$this->assertEquals($result, 1, "Wrapper::insert (full) didn't insert");
		/**
		 * Verify Rows
		 */
		$expected = array("first sprocket");

		$sql = "select title from sprockets where type_id in (24, 25, 26) order by type_id;";

		$result = $this->dbConn->scalars($sql);
		$this->assertEquals($result, $expected, "Wrapper::insert(s) did not verify");
	}

	/**
	 * @depends testScalars
	 */
	public function testInsertPartial(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$this->setUpSprockets(false);

		$data = array(
			"title"   => "second sprocket",
			"type_id" => "25",
		);

		// insert partial
		$result = $this->dbConn->insert("sprockets", $data);
		$this->assertEquals($result, 1, "Wrapper::insert (partial) didn't insert");

		/**
		 * Verify Rows
		 */
		$expected = array("second sprocket");

		$sql = "select title from sprockets where type_id in (24, 25, 26) order by type_id;";

		$result = $this->dbConn->scalars($sql);
		$this->assertEquals($result, $expected, "Wrapper::insert(s) did not verify");
	}

	/**
	 * @depends testScalars
	 */
	public function testInsertFuncs(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$this->setUpSprockets(false);

		$data = array(
			"title"     => "third sprocket",
			"descr"     => array(true, "UUID()"),
			"type_id"   => "26",
			"updated"   => array(true, "NOW()"),
		);

		// insert with funcs
		$result = $this->dbConn->insert("sprockets", $data);
		$this->assertEquals($result, 1, "Wrapper::insert (with funcs) didn't insert");

		/**
		 * Verify Rows
		 */
		$expected = array("third sprocket");

		$sql = "select title from sprockets where type_id in (24, 25, 26) order by type_id;";

		$result = $this->dbConn->scalars($sql);
		$this->assertEquals($result, $expected, "Wrapper::insert(s) did not verify");
	}

	/**
	 * @depends testAssoc
	 */
	public function testReplaceFull(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$this->setUpSprockets(true);

		$data = array(
			"title"       => "REPLACED 1",
			"sprocket_id" => "1",
			"descr"       => array(true, "UUID()"),
			"type_id"     => "24",
			"updated"     => array(true, "NOW()"),
		);

		// replace full ... we expect 2 because of a delete & an insert
		$result = $this->dbConn->replace("sprockets", $data);
		$this->assertEquals($result, 2, "Wrapper::replace (full) didn't insert");

		/**
		 * Verify Rows
		 */
		$sql = "select descr, updated from sprockets where sprocket_id in (1, 2) order by sprocket_id;";

		// uuid should change
		$result = $this->dbConn->assoc($sql);

		// uuid should change
		$expected = "6eb55118-a949-11e2-910d-973c674f56c2";
		$this->assertNotEquals($result[0]["descr"], $expected, "Wrapper::replace (full) did not verify");

	}

	/**
	 * @depends testAssoc
	 */
	public function testReplacePartial(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$this->setUpSprockets(true);

		$data = array(
			"title"       => "REPLACED 2",
			"sprocket_id" => "2",
			"descr"       => array(true, "UUID()"),
		);

		// replace partial ... we expect 2 because of a delete & an insert
		$result = $this->dbConn->replace("sprockets", $data);
		$this->assertEquals($result, 2, "Wrapper::replace (partial) didn't insert");

		/**
		 * Verify Rows
		 */
		$sql = "select descr, updated from sprockets where sprocket_id in (1, 2) order by sprocket_id;";

		// uuid should change
		$result = $this->dbConn->assoc($sql);

		// uuid should change
		$expected = "6eb55118-a949-11e2-910d-973c674f56c2";
		$this->assertNotEquals($result[1]["descr"], $expected, "Wrapper::replace (partial) did not verify");

		// partial replace should blank some columns
		$expected = null;
		$this->assertEquals($result[1]["updated"], $expected, "Wrapper::replace (partial) did not verify");

	}

	/**
	 * @depends testScalars
	 */
	public function testMultiInsert(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$this->setUpSprockets(false);

		/**
		 * column counts MUST match for a multi_insert and funcs are applied to each insert
		 * Basically, every row your inserting needs to have the same structure
		 */
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

		// insert multi
		$result = $this->dbConn->multi_insert("sprockets", $data);
		$this->assertEquals($result, 3, "Wrapper::insert (multi) didn't insert");

		/**
		 * Verify Rows
		 */
		$sql = "select title from sprockets where type_id in (45, 46) order by sprocket_id;";

		$result = $this->dbConn->scalars($sql);

		$expected = "multi sprocket 45";
		$this->assertEquals($result[0], $expected, "Wrapper::insert (multi) did not verify");

		$expected = "multi sprocket 46";
		$this->assertEquals($result[1], $expected, "Wrapper::insert (multi) did not verify");

	}

	/**
	 * @depends testAssoc
	 */
	public function testMultiReplace(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$this->setUpSprockets(true);

		/**
		 * column counts MUST match for a multi_insert and funcs are applied to each insert
		 * Basically, every row your inserting needs to have the same structure
		 */
		$data = array(
			array(
				"title"       => "REPLACED 1",
				"sprocket_id" => "1",
				"descr"       => array(true, "UUID()"),
			),
			array(
				"title"       => "REPLACED 2",
				"sprocket_id" => "2",
				"descr"       => array(true, "UUID()"),
			),
		);

		// replace full ... we expect 4 because of a 2 delete(s) & 2 insert(s)
		$result = $this->dbConn->multi_replace("sprockets", $data);
		$this->assertEquals($result, 4, "Wrapper::replace (multi) didn't insert");

		/**
		 * Verify Rows
		 */
		$sql = "select descr, updated from sprockets where sprocket_id in (1, 2) order by sprocket_id;";

		// uuid should change
		$result = $this->dbConn->assoc($sql);

		// uuid should change
		$expected = "6eb55118-a949-11e2-910d-973c674f56c2";
		$this->assertNotEquals($result[0]["descr"], $expected, "Wrapper::replace (multi) did not verify");

		// uuid should change
		$expected = "6eb55118-a949-11e2-910d-973c674f56c2";
		$this->assertNotEquals($result[1]["descr"], $expected, "Wrapper::replace (multi) did not verify");

		// partial replace should blank some columns
		$expected = null;
		$this->assertEquals($result[1]["updated"], $expected, "Wrapper::replace (multi) did not verify");
	}

	/**
	 * @depends testRow
	 */
	public function testUpdate(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$this->setUpSprockets(true);

		$data = array(
			"title"     => "update sprocket title",
			"descr"     => "update sprocket descr",
			"type_id"   => "45",
			"updated"   => array(true, "NULL"),
		);

		$where = array(
			"sprocket_id" => "8"
		);

		$expected = array(
			0             => "update sprocket title",
			"title"       => "update sprocket title",
			1             => "8",
			"sprocket_id" => "8",
			2             => "update sprocket descr",
			"descr"       => "update sprocket descr",
			3             => "45",
			"type_id"     => "45",
			4             => null,
			"updated"     => null,
		);

		$result = $this->dbConn->update("sprockets", $data, $where);
		$this->assertEquals($result, 1, "Wrapper::update didn't update");

		/**
		 * Verify Rows
		 */
		$sql = "select * from sprockets where sprocket_id = 8;";

		$result = $this->dbConn->row($sql);
		$this->assertEquals($result, $expected, "Wrapper::update did not verify");
		$this->assertInternalType("null", $result["updated"], "Wrapper::update did not verify");
	}

	/**
	 * @depends testRow
	 */
	public function testUpdateOnDuplicateKey(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$this->setUpSprockets(true);

		$data = array(
			"title"     => "update sprocket title",
			"descr"     => "update sprocket descr",
			"type_id"   => "45",
			"updated"   => array(true, "NULL"),
		);

		$where = array(
			"sprocket_id" => "8"
		);

		$expected = array(
			0             => "update sprocket title",
			"title"       => "update sprocket title",
			1             => "8",
			"sprocket_id" => "8",
			2             => "update sprocket descr",
			"descr"       => "update sprocket descr",
			3             => "45",
			"type_id"     => "45",
			4             => null,
			"updated"     => null,
		);

		// updates report 2 affected rows, as per the MySQL manual
		$result = $this->dbConn->on_duplicate_key("sprockets", $data, $where);
		$this->assertEquals($result, 2, "Wrapper::update didn't update");

		/**
		 * Verify Rows
		 */
		$sql = "select * from sprockets where sprocket_id = 8;";

		$result = $this->dbConn->row($sql);
		$this->assertEquals($result, $expected, "Wrapper::update did not verify");
		$this->assertInternalType("null", $result["updated"], "Wrapper::update did not verify");
	}

	/**
	 * @depends testRow
	 */
	public function testInsertOnDuplicateKey(){
		// $this->markTestSkipped("Wrapper requires an active DB connection");

		$this->setUpSprockets(true);

		$data = array(
			"title"     => "update sprocket title",
			"descr"     => "update sprocket descr",
			"type_id"   => "45",
			"updated"   => array(true, "NULL"),
		);

		$where = array(
			"sprocket_id" => "18"
		);

		$expected = array(
			0             => "update sprocket title",
			"title"       => "update sprocket title",
			1             => "18",
			"sprocket_id" => "18",
			2             => "update sprocket descr",
			"descr"       => "update sprocket descr",
			3             => "45",
			"type_id"     => "45",
			4             => null,
			"updated"     => null,
		);

		$result = $this->dbConn->on_duplicate_key("sprockets", $data, $where);
		$this->assertEquals($result, 1, "Wrapper::update didn't update");

		/**
		 * Verify Rows
		 */
		$sql = "select * from sprockets where sprocket_id = 18;";

		$result = $this->dbConn->row($sql);
		$this->assertEquals($result, $expected, "Wrapper::update did not verify");
		$this->assertInternalType("null", $result["updated"], "Wrapper::update did not verify");
	}

}