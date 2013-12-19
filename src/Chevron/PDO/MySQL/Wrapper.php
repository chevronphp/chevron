<?php

namespace Chevron\PDO\MySQL;
/**
 * A DB wrapper class offering some helpful shortcut methods
 *
 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
 *
 * @package Chevron\PDO\MySQL
 * @author Jon Henderson
 */
class Wrapper extends \Chevron\PDO\Connector implements \Chevron\PDO\Interfaces\WrapperInterface {

	public $debug       = false;
	public $num_retries = 5;
	public $lastQry     = "";
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

		return $this->exe_return_count($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function update($table, array $map, array $where = array()){
		$pdoq = new Query;
		$query = $pdoq->update($table, $map, $where);

		$data = $pdoq->filter_data($map, $where);

		return $this->exe_return_count($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function replace($table, array $map){
		$pdoq  = new Query;
		$query = $pdoq->replace($table, $map, 0);
		$data  = $pdoq->filter_data($map);
		return $this->exe_return_count($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function on_duplicate_key($table, array $map, array $where){
		$pdoq  = new Query;
		$query = $pdoq->on_duplicate_key($table, $map, $where);
		$data  = $pdoq->filter_data($map, $where, $map);
		return $this->exe_return_count($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function multi_insert($table, array $map){
		$pdoq  = new Query;
		$query = $pdoq->insert($table, $map, count($map));
		$data  = $pdoq->filter_multi_data($map);
		return $this->exe_return_count($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function multi_replace($table, array $map){
		$pdoq  = new Query;
		$query = $pdoq->replace($table, $map, count($map));
		$data  = $pdoq->filter_multi_data($map);
		return $this->exe_return_count($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	protected function exe_return_count($query, array $data){

		$statement = $this->validate_and_prepare($query, count($data));

		$retry = $this->num_retries;
		while( $retry-- ){
			$success = $statement->execute($data);

			if( $success ){
			return $statement->rowCount();
			}

			// deadlock
			if( $statement->errorCode() == "40001" ){ continue; }

			throw new \Exception($this->error($statement));
		}
		throw new \Exception("Query Failed after 5 attempts:\n\n{$query}");
	}
##### SELECT HELPERS
################################################################################
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function exe($query, array $map = array(), $in = false){
		return $this->exe_return_result($query, $map, $in);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function assoc($query, array $map = array(), $in = false){
		$result = $this->exe_return_result($query, $map, $in, \PDO::FETCH_ASSOC);
		return iterator_to_array($result) ?: array();
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function row($query, array $map = array(), $in = false){
		$result = $this->exe_return_result($query, $map, $in);
		foreach($result as $row){ return $row; }
		return array();
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function scalar($query, array $map = array(), $in = false){
		$result = $this->exe_return_result($query, $map, $in);
		foreach($result as $row){ return $row[0]; }
		return null;
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function scalars($query, array $map = array(), $in = false){
		$result = $this->exe_return_result($query, $map, $in);
		$final = array();
		foreach($result as $row){ $final[] = $row[0]; }
		return $final;
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function keypair($query, array $map = array(), $in = false){
		$result = $this->exe_return_result($query, $map, $in);
		$final = array();
		foreach($result as $row){
			$final[$row[0]] = $row[1];
		}
		return $final ?: array();
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function keyrow($query, array $map = array(), $in = false){
		$result = $this->exe_return_result($query, $map, $in);
		$final = array();
		foreach($result as $row){
			$final[$row[0]] = $row;
		}
		return $final ?: array();
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function keyrows($query, array $map = array(), $in = false){
		$result = $this->exe_return_result($query, $map, $in);
		$final = array();
		foreach($result as $row){
			$final[$row[0]][] = $row;
		}
		return $final ?: array();
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	protected function exe_return_result($query, array $map, $in, $fetch = \PDO::FETCH_BOTH){

		$pdoq = new Query;
		if($in){
			// this syntax (returning an array with two values) is a little more
			// esoteric than i'd prefer ... but it works
			list( $query, $map ) = $pdoq->in( $query, $map );
		}

		// redundant for IN queries since the data is already flat
		$data = $pdoq->filter_data($map);

		$statement = $this->validate_and_prepare($query, count($data));

		$statement->setFetchMode($fetch);

		$retry = $this->num_retries;
		while( $retry-- ){
			$success = $statement->execute($data);

			if( $success ){
			if( $statement->columnCount() ){
				// only queries that return a result set should have a column count
				return new \IteratorIterator($statement);
			}
			// successful queries that do not return a result
			return true;
			}

			// deadlock
			if( $statement->errorCode() == "40001" ){ continue; }

			throw new \Exception($this->error($query));
		}

		throw new \Exception("Query Failed after 5 attempts:\n\n{$query}");

	}
##### UTIL HELPERS
################################################################################
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function __call($name, $args){

		$PDOR = new \ReflectionClass($this->conn);
		if( $PDOR->hasMethod($name) ){
			$method = $PDOR->getMethod($name);
			return $method->invokeArgs($this->conn, $args);
		}

		throw new \Exception("Method PDO::{$name} doesn't exist.");
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	protected function validate_and_prepare($query, $param_count){
		if( !($query InstanceOf \PDOStatement ) ){
			$query = strtr($query, "\t", " ");
			$statement = $this->conn->prepare($query);
		}

		$msg = sprintf("Query: %s \n\nToken Count: %s", $statement->queryString, $param_count);
		$this->lastQry = $msg;

		if($this->debug){
			throw new \Exception("PDO Debug:\n{$msg}");
		}

		if( substr_count($statement->queryString, "?") != $param_count ){
			throw new \Exception("Token count doesn't match the data count.");
		}

		return $statement;

	}
	/**
	 * Beautifies an error message to display
	 * @param PDOException $obj
	 * @param bool $rtn A flag to toggle an exit or return
	 * @return mixed
	 */
	function error($obj, $rtn = true){
		$err   = $obj->errorInfo();
		$err[] = $obj->queryString;

		$str =  "The DB dropped an error:\n\n" .
				"### SQLSTATE // Error Code ###\n" .
				"      %s // %s\n\n" .
				"### Error Message ###\n" .
				"      %s\n\n" .
				"### Query ###\n" .
				"      %s\n\n";

		$str = vsprintf($str, $err);

		if( $rtn )
			return $str;

		printf($str);
		exit();
	}

}

