<?php

namespace Chevron\Logger;

use \Psr\Log\AbstractLogger;
use \Chevron\DB\Interfaces\PDOWrapperInterface;

class MySQLLogger extends AbstractLogger {

	protected $dbConn, $table;

	public function __construct( PDOWrapperInterface $dbConn, $table = "logging" ){
		$this->dbConn = $dbConn;
		$this->table   = $table;
	}

	public function log( $level, $message, array $context = array() ) {
		$this->dbConn->insert($this->table, array(
			"created" => array(true, "NOW()"),
			"level"   => $level,
			"message" => $message,
			"context" => serialize($context),
		));
	}

}
