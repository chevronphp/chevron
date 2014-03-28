<?php

namespace Chevron\PDO\Mock;
/**
 * This class implements the WrapperInterface but every method returns
 * whatever data you've stored in $next (via next()) in a FIFO way.
 * This should allow for the testing of DB dependent functionality by
 * eliminating a live DB.
 */
class FIFO implements \Chevron\PDO\Interfaces\WrapperInterface {

	protected $next = array();

	/**
	 * Operate a FIFO stack of return values
	 * @param mixed $next The value to return
	 * @return mixed
	 */
	function next($next){
		$this->next[] = $next;
	}
	/**
	 * Enforce a FIFO stack
	 * @return mixed
	 */
	function shift(){
		return array_shift($this->next);
	}

	function put($table, array $map, array $where = array()){
		return $this->shift();
	}

	function insert($table, array $map){
		return $this->shift();
	}

	function update($table, array $map, array $where = array()){
		return $this->shift();
	}

	function replace($table, array $map){
		return $this->shift();
	}

	function on_duplicate_key($table, array $map, array $where){
		return $this->shift();
	}

	function multi_insert($table, array $map){
		return $this->shift();
	}

	function multi_replace($table, array $map){
		return $this->shift();
	}

	function exe($query, array $map = array(), $in = false){
		return $this->shift();
	}

	function assoc($query, array $map = array(), $in = false){
		return $this->shift();
	}

	function row($query, array $map = array(), $in = false){
		return $this->shift();
	}

	function scalar($query, array $map = array(), $in = false){
		return $this->shift();
	}

	function scalars($query, array $map = array(), $in = false){
		return $this->shift();
	}

	function keypair($query, array $map = array(), $in = false){
		return $this->shift();
	}

	function __call($name, $args){
		return $this->shift();
	}

	function __construct($dsn, $username, $password){}

}