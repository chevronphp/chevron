<?php

namespace Chevron\PDO\MySQL;
/**
 * This class implements the WrapperInterface but every method returns
 * whatever data you've stored in $next (via next()). This should allow
 * for the testing of DB dependent functionality by eliminating a live
 * DB.
 */
class Mock implements WrapperInterface {

	protected $next;

	function next($next){
		$this->next = $next;
	}

	function put($table, array $map, array $where = array()){
		return $this->next;
	}

	function insert($table, array $map){
		return $this->next;
	}

	function update($table, array $map, array $where = array()){
		return $this->next;
	}

	function replace($table, array $map){
		return $this->next;
	}

	function on_duplicate_key($table, array $map, array $where){
		return $this->next;
	}

	function multi_insert($table, array $map){
		return $this->next;
	}

	function multi_replace($table, array $map){
		return $this->next;
	}

	function exe($query, array $map = array(), $in = false){
		return $this->next;
	}

	function assoc($query, array $map = array(), $in = false){
		return $this->next;
	}

	function row($query, array $map = array(), $in = false){
		return $this->next;
	}

	function scalar($query, array $map = array(), $in = false){
		return $this->next;
	}

	function scalars($query, array $map = array(), $in = false){
		return $this->next;
	}

	function keypair($query, array $map = array(), $in = false){
		return $this->next;
	}

	function __call($name, $args){
		return $this->next;
	}

	function __construct($dsn, $username, $password){}

}