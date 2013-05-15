<?php

namespace Chevron\PDO\MySQL;
/**
 * A DB wrapper class offering some helpful shortcut methods that return a
 * query string **properly** escaped. This SHOULD NEVER be used in a production
 * environment. This is for generating long SQL files of TRUSTED data
 * from TRUSTED sources.
 *
 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
 *
 * @package Chevron\PDO\MySQL
 * @author Jon Henderson
 */
class Sprintfer extends \Chevron\PDO\Connector {
##### PUT HELPERS
################################################################################
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function put($table, array $map, array $where = array()){
		if( $where ){
			return $this->update($table, $map, $where);
		}else{
			return $this->insert($table, $map, 0);
		}
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function insert($table, array $map){
		$pdoq = new Query;
		$query = $pdoq->insert($table, $map, 0);

		$data = $pdoq->filter_data($map);

		return $this->return_quoted_string($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function update($table, array $map, array $where = array()){
		$pdoq = new Query;
		$query = $pdoq->update($table, $map, $where);

		$data = $pdoq->filter_data($map, $where);

		return $this->return_quoted_string($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function replace($table, array $map){
		$pdoq  = new Query;
		$query = $pdoq->replace($table, $map, 0);
		$data  = $pdoq->filter_data($map);
		return $this->return_quoted_string($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function on_duplicate_key($table, array $map, array $where){
		$pdoq  = new Query;
		$query = $pdoq->on_duplicate_key($table, $map, $where);
		$data  = $pdoq->filter_data($map, $where, $map);
		return $this->return_quoted_string($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function multi_insert($table, array $map){
		$pdoq  = new Query;
		$query = $pdoq->insert($table, $map, count($map));
		$data  = $pdoq->filter_data($map);
		return $this->return_quoted_string($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function multi_replace($table, array $map){
		$pdoq  = new Query;
		$query = $pdoq->replace($table, $map, count($map));
		$data  = $pdoq->filter_data($map);
		return $this->return_quoted_string($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	protected function return_quoted_string($query, array $data){

		$query = str_replace("?", "%s", $query);

		$conn = $this->conn;
		array_walk($data, function(&$v)use($conn){
			$v = $conn->quote($v);
		});

		$query = vsprintf($query, $data);

		return $query;
	}

}

