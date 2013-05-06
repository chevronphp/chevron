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
	 * For documentation, consult the Interface (__DIR__ . "/DBInterface.php")
	 */

	function __construct($dsn, $username, $password){
		try {
			$this->conn = new \PDO( $dsn, $username, $password );
		} catch (PDOException $e) {
		    trigger_error($e->getMessage());
		}
	}
}