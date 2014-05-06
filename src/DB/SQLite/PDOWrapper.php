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
}

