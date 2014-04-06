<?php

namespace Chevron\DB\SQLite;
/**
 * A DB wrapper class offering some helpful shortcut methods
 *
 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
 *
 * @package Chevron\PDO\MySQL
 * @author Jon Henderson
 */
class WIP_Wrapper extends \PDO {

	use \Chevron\DB\Traits\QueryHelperTrait;

	public $debug       = false;
	public $num_retries = 5;
	protected $inspector;
	/**
	 * Method to set a lambda as an inspector pre query
	 * @param type callable $func
	 * @return type
	 */
	function registerInspector(callable $func){
		$this->inspector = $func;
	}
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

		list($columns, $tokens) = $this->parenPairs($map, 0);
		$query = sprintf("INSERT INTO `%s` %s VALUES %s;", $table, $columns, $tokens);
		$data = $this->filterData($map);
		return $this->exe_return_count($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function update($table, array $map, array $where = array()){

		$column_map      = $this->equalPairs($map, ", ");
		$conditional_map = $this->equalPairs($where, " and ");
		$query = sprintf("UPDATE `%s` SET %s WHERE %s;", $table, $column_map, $conditional_map);
		$data = $this->filterData($map, $where);
		return $this->exe_return_count($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function replace($table, array $map){

		list($columns, $tokens) = $this->parenPairs($map, 0);
		$query = sprintf("INSERT OR REPLACE INTO %s %s VALUES %s;", $table, $columns, $tokens);
		$data  = $this->filterData($map);
		return $this->exe_return_count($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 *
	 * via http://stackoverflow.com/questions/418898/sqlite-upsert-not-insert-or-replace
	 * If you are generally doing updates I would ..
	 *   - Begin a transaction, Do the update, Check the rowcount, If it is 0 do the insert, Commit
	 *
	 * If you are generally doing inserts I would
	 *   - Begin a transaction, Try an insert, Check for primary key violation error, if we got an error do the update, Commit
	 *
	 */
	function on_duplicate_key($table, array $map, array $where){

		$count = $this->update($table, $map, $where);

		if($count === 0){
			$data = array_merge($map, $where);
			$count = $this->insert($table, $data, 0);
		}

		return $count;
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function multi_insert($table, array $map){

		list($columns, $tokens) = $this->parenPairs($map, count($map));
		$query = sprintf("INSERT INTO `%s` %s VALUES %s;", $table, $columns, $tokens);
		$data  = $this->filterMultiData($map);
		return $this->exe_return_count($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function multi_replace($table, array $map){

		list($columns, $tokens) = $this->parenPairs($map, count($map));
		$query = sprintf("INSERT OR REPLACE INTO `%s` %s VALUES %s;", $table, $columns, $tokens);
		$data  = $this->filterMultiData($map);
		return $this->exe_return_count($query, $data);
	}
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

			// deadlock -- this is a mysql number.
			if( $statement->errorCode() == "40001" ){ continue; }

			throw new \PDOException($this->fError($statement));
		}
		throw new \PDOException("Query Failed after 5 attempts:\n\n{$query}");
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	protected function exe_return_result($query, array $map, $in, $fetch = \PDO::FETCH_BOTH){

		if($in){
			// this syntax (returning an array with two values) is a little more
			// esoteric than i'd prefer ... but it works
			list( $query, $map ) = $this->in( $query, $map );
		}

		// redundant for IN queries since the data is already flat
		$data = $this->filterData($map);

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

			// deadlock -- this is a mysql number.
			if( $statement->errorCode() == "40001" ){ continue; }

			throw new \PDOException($this->fError($statement));
		}

		throw new \PDOException("Query Failed after 5 attempts:\n\n{$query}");

	}
	/**
	 * Beautifies an error message to display
	 * @param PDOException $obj
	 * @param bool $rtn A flag to toggle an exit or return
	 * @return mixed
	 */
	protected function fError($obj, $data = null, $rtn = true){

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

