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
class Wrapper extends \PDO implements \Chevron\PDO\Interfaces\WrapperInterface {

	public    $debug      = false;
	public    $numRetries = 5;
	protected $inspector;
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

		if(is_callable($this->inspector)){
			call_user_func($this->inspector, $this, $query, $data);
		}

		$statement = $this->prepare($query);
		// if( !($query InstanceOf \PDOStatement ) ){}

		$retry = $this->numRetries;
		while( $retry-- ){
			try{
				$success = $statement->execute($data);
			}catch(\Exception $e){
				throw new \PDOException($this->fError($statement, $data));
			}

			if( $success ){
				return $statement->rowCount();
			}

			// deadlock
			if( $statement->errorCode() == "40001" ){ continue; }

			throw new \PDOException($this->fError($statement));
		}
		throw new \PDOException("Query Failed after 5 attempts:\n\n{$query}");
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

		if(is_callable($this->inspector)){
			call_user_func($this->inspector, $this, $query, $data);
		}

		$statement = $this->prepare($query);
		// if( !($query InstanceOf \PDOStatement ) ){}

		$statement->setFetchMode($fetch);

		$retry = $this->numRetries;
		while( $retry-- ){
			try{
				$success = $statement->execute($data);
			}catch(\Exception $e){
				throw new \PDOException($this->fError($statement, $data));
			}

			if( $success ){
				if( $statement->columnCount() ){
					// only queries that return a result set should have a column count
					return new \IteratorIterator($statement);
				}
				return true;
			}

			// deadlock
			if( $statement->errorCode() == "40001" ){ continue; }

			throw new \PDOException($this->fError($statement));
		}

		throw new \PDOException("Query Failed after 5 attempts:\n\n{$query}");

	}

	/**
	 * Method to set a lambda as an inspector pre query
	 * @param type callable $func
	 * @return type
	 */
	function registerInspector(callable $func){
		$this->inspector = $func;
	}
##### UTIL HELPERS
################################################################################
	/**
	 * Beautifies an error message to display
	 * @param PDOException $obj
	 * @param bool $rtn A flag to toggle an exit or return
	 * @return mixed
	 */
	function fError($obj, $data = null, $rtn = true){
		$err   = $obj->errorInfo();
		$err[] = $obj->queryString;

		$str =  "The DB dropped an error:\n\n" .
				"### SQLSTATE // Error Code ###\n" .
				"      %s // %s\n\n" .
				"### Error Message ###\n" .
				"      %s\n\n" .
				"### Query ###\n" .
				"      %s\n\n";

		if($data){
			$err[] = count($data);
			$str .= "### Parameter Count ###\n".
					"      %s";
		}

		$str = vsprintf($str, $err);

		if( $rtn ) return $str;

		printf($str);
		exit();
	}

}

