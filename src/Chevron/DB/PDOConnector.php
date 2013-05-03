<?php

namespace Chevron\DB;
/**
 * A simple PDO constuctor
 *
 * @package Chevron\DB
 * @author Jon Henderson
 */
class PDOConnector {
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