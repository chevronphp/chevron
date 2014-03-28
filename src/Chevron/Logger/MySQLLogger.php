<?php

namespace Chevron\Logger;

use \Psr\Log\AbstractLogger;
use \Chevron\PDO\Interfaces\WrapperInterface;

class MySQLLogger extends AbstractLogger {

	protected $dbConn, $table;

	public function __construct( WrapperInterface $dbConn, $table = "logging" ){
		$this->dbWrite = $dbWrite;
		$this->table   = $table;
	}

	public function log( $level, $message, array $context = array() ) {
		$this->dbWrite->insert($this->table, array(
			"created" => array(true, "NOW()"),
			"level"   => $level,
			"message" => $message,
			"context" => serialize($context),
		));
	}

}
