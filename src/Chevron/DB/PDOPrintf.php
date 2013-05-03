<?php

namespace Chevron\DB;
/**
 *
 * A package to emulate the PDOWrapper class -- returning the string query
 * with the data as an array. Useful for generating properly formatted queries
 * and pairing it with the relevant data for use in a queue or for logging.
 *
 * @package Chevron\DB
 * @author Jon Henderson
 */
class PDOPrinf {
##### PUT HELPERS
################################################################################
	/**
	 * For documentation, consult the Interface (__DIR__ . "/DBInterface.php")
	 */
	function put($table, array $map, array $where = array()){
		if( $where ){
			return $this->update($table, $map, $where);
		}else{
			return $this->insert($table, $map, 0);
		}
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/DBInterface.php")
	 */
	function insert($table, array $map){
		$pdoq = new SQLQ;
		$query = $pdoq->insert($table, $map, 0);

		$data = $pdoq->filter_data($map);

		return array($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/DBInterface.php")
	 */
	function update($table, array $map, array $where = array()){
		$pdoq = new SQLQ;
		$query = $pdoq->update($table, $map, $where);

		$data = $pdoq->filter_data($map, $where);

		return array($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/DBInterface.php")
	 */
	function replace($table, array $map){
		$pdoq  = new SQLQ;
		$query = $pdoq->replace($table, $map, 0);
		$data  = $pdoq->filter_data($map);
		return array($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/DBInterface.php")
	 */
	function on_duplicate_key($table, array $map, array $where){
		$pdoq  = new SQLQ;
		$query = $pdoq->on_duplicate_key($table, $map, $where);
		$data  = $pdoq->filter_data($map, $where, $map);
		return array($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/DBInterface.php")
	 */
	function multi_insert($table, array $map){
		$pdoq  = new SQLQ;
		$query = $pdoq->insert($table, $map, count($map));
		$data  = $pdoq->filter_data($map);
		return array($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/DBInterface.php")
	 */
	function multi_replace($table, array $map){
		$pdoq  = new SQLQ;
		$query = $pdoq->replace($table, $map, count($map));
		$data  = $pdoq->filter_data($map);
		return array($query, $data);
	}
}

