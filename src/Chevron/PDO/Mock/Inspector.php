<?php

namespace Chevron\PDO\Mock;
/**
 * This class implements the WrapperInterface but every method returns
 * whatever data you've stored in $next (via next()) in a FIFO way.
 * This should allow for the testing of DB dependent functionality by
 * eliminating a live DB.
 */
class Inspector implements \Chevron\PDO\Interfaces\WrapperInterface {
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

		return array($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function update($table, array $map, array $where = array()){
		$pdoq = new Query;
		$query = $pdoq->update($table, $map, $where);

		$data = $pdoq->filter_data($map, $where);

		return array($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function replace($table, array $map){
		$pdoq  = new Query;
		$query = $pdoq->replace($table, $map, 0);
		$data  = $pdoq->filter_data($map);
		return array($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function on_duplicate_key($table, array $map, array $where){
		$pdoq  = new Query;
		$query = $pdoq->on_duplicate_key($table, $map, $where);
		$data  = $pdoq->filter_data($map, $where, $map);
		return array($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function multi_insert($table, array $map){
		$pdoq  = new Query;
		$query = $pdoq->insert($table, $map, count($map));
		$data  = $pdoq->filter_multi_data($map);
		return array($query, $data);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function multi_replace($table, array $map){
		$pdoq  = new Query;
		$query = $pdoq->replace($table, $map, count($map));
		$data  = $pdoq->filter_multi_data($map);
		return array($query, $data);
	}
##### SELECT HELPERS
################################################################################
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function exe($query, array $map = array(), $in = false){
		return array($query, $map, $in);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function assoc($query, array $map = array(), $in = false){
		return array($query, $map, $in);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function row($query, array $map = array(), $in = false){
		return array($query, $map, $in);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function scalar($query, array $map = array(), $in = false){
		return array($query, $map, $in);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function scalars($query, array $map = array(), $in = false){
		return array($query, $map, $in);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function keypair($query, array $map = array(), $in = false){
		return array($query, $map, $in);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function keyrow($query, array $map = array(), $in = false){
		return array($query, $map, $in);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
	 */
	function keyrows($query, array $map = array(), $in = false){
		return array($query, $map, $in);
	}
}

