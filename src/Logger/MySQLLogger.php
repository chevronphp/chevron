<?php

namespace Chevron\Logger;

use \Psr\Log\AbstractLogger;
use \Chevron\DB\Interfaces\PDOWrapperInterface;
/**
 * implement the Psr3 Logger mixed with mysql via Chevron\DB
 * @package Chevron\Logger
 */
class MySQLLogger extends AbstractLogger {

	/**
	 * The db object
	 */
	protected $dbConn;

	/**
	 * The table in which to store logs
	 */
	protected $table;

	/**
	 * method to create a logger object tied to a DB connection and table
	 * @param PDOWrapperInterface $dbConn The db connection
	 * @param string $table The table name
	 * @return
	 */
	public function __construct( PDOWrapperInterface $dbConn, $table = "logging" ){
		$this->dbConn = $dbConn;
		$this->table  = $table;
	}

	/**
	 * method to write data to the log -- as of now the default table contains only
	 * room for an MD5 hash for the message. The table can be modded to hold anything
	 * for the message but that might affect the column type for `message`
	 * @param string $level The level of the log message -- use Psr3 constants
	 * @param string $message A brief message
	 * @param array $context An array of additional information to store with the log
	 * @return
	 */
	public function log( $level, $message, array $context = array() ) {
		$this->dbConn->insert($this->table, array(
			"created" => array(true, "NOW()"),
			"level"   => $level,
			"message" => $message,
			"context" => serialize($context),
		));
	}

}


/*
CREATE TABLE `logging` (
	`logging_id` int(11) NOT NULL AUTO_INCREMENT,
	`created` datetime DEFAULT NULL,
	`level` enum('emergency','alert','critical','error','warning','notice','info','debug') DEFAULT 'info',
	`message` char(24) DEFAULT NULL,
	`context` mediumblob DEFAULT NULL,
	PRIMARY KEY (`logging_id`),
	INDEX `level` USING BTREE (`level`) comment '',
	INDEX `message` USING BTREE (`message`) comment '',
	INDEX `created` USING BTREE (`created`) comment ''
) ENGINE=`InnoDB` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
*/