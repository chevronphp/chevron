<?php

namespace Chevron\PDO;
/**
 * A simple PDO constuctor
 *
 * @package Chevron\PDO\MySQL
 * @author Jon Henderson
 */
class Connector {
	/**
	 * Hold our connection
	 */
	protected $conn;
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */

	function __construct($dsn, $username, $password){
			$this->conn = new \PDO( $dsn, $username, $password );
	}
}
