<?php

namespace Chevron\PDO\MySQL;
/**
 *
 * For documentation, consult the Interface (__DIR__ . "/DBInterface.php")
 *
 * @package DB
 */
class Queries implements QueriesInterface {

	protected $namedTokens = false;
	protected $columns, $tokens;

	/**
	 * For documentation, consult the Interface (__DIR__ . "/DBQInterface.php")
	 */
	function __construct($tokens = false){
		if($tokens){
			$this->namedTokens = true;
		}
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/DBQInterface.php")
	 */
	function insert($table, array $map, $multiple){
		list($columns, $tokens) = $this->paren_pairs($map, $multiple);
		$_SQL   = sprintf("INSERT INTO %s %s VALUES %s;", $table, $columns, $tokens);
		return $_SQL;
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/DBQInterface.php")
	 */
	function replace($table, array $map, $multiple){
		list($columns, $tokens) = $this->paren_pairs($map, $multiple);
		$_SQL = sprintf("REPLACE INTO %s %s VALUES %s;", $table, $columns, $tokens);
		return $_SQL;
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/DBQInterface.php")
	 */
	function update($table, array $map, array $where){
		$column_map      = $this->equal_pairs($map, ", ");
		$conditional_map = $this->equal_pairs($where, " and ");
		$_SQL = sprintf("UPDATE %s SET %s WHERE %s;", $table, $column_map, $conditional_map);
		return $_SQL;
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/DBQInterface.php")
	 */
	function on_duplicate_key($table, array $map, array $where){
		$column_map      = $this->equal_pairs($map, ", ");
		$conditional_map = $this->equal_pairs($where, ", ");
		$_SQL = sprintf("INSERT INTO %s SET %s, %s ON DUPLICATE KEY UPDATE %s;", $table, $column_map, $conditional_map, $column_map);
		return $_SQL;
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/DBQInterface.php")
	 */
	function in($query, array $map){
		$iter1 = new \ArrayIterator($map);
		$final = $replacements = array();
		foreach( $iter1 as $key => $value ){
			if(is_array($value)){
				$replacements[] = rtrim(str_repeat("?, ", count($value)), ", ");
			}
			$final = array_merge($final, (array)$value);
		}
		$_SQL = vsprintf($query, $replacements);
		return array( $_SQL, $final );
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/DBQInterface.php")
	 */
	function filter_data(){
		$iter1 = new \RecursiveArrayIterator(func_get_args());
		$iter2 = new \RecursiveIteratorIterator($iter1);
		// arrays should never be more than 2 deep
		$iter2->setMaxDepth(2);

		$final = array();
		// re:REWIND ... http://stackoverflow.com/questions/13555884/recursiveiteratoriterator-returns-extra-elements
		for($iter2->rewind(); $iter2->valid(); $iter2->next()){
			$iter3 = $iter2->getInnerIterator();
			$count[] = $iter3->count();
			foreach($iter3 as $key => $value){
				if(is_scalar($value)){
					$final[] = $value;
				}
				// $keys[$key] = $key; // to use for validation
			}
		}
		return $final;
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/DBQInterface.php")
	 */
	protected function paren_pairs(array $map, $multiple){
		list($columns, $tokens) = $this->map_columns($map);

		$columns = sprintf("(`%s`)", implode("`, `", $columns));
		$tokens  = sprintf("(%s)",   implode(", ",   $tokens));

		if($multiple){
			$tokens = rtrim(str_repeat("{$tokens},", $multiple), ",");
		}

		return array( $columns, $tokens );
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/DBQInterface.php")
	 */
	protected function equal_pairs(array $map, $sep = ", "){
		list($columns, $tokens) = $this->map_columns($map);

		$count = count($columns);
		for( $i = 0; $i < $count; ++$i ){
			$temp[] = "`{$columns[$i]}` = {$tokens[$i]}";
		}

		return implode($sep, $temp);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/DBQInterface.php")
	 */
	protected function map_columns(array $map){
		$iter1 = new \RecursiveArrayIterator($map);
		$iter2 = new \RecursiveIteratorIterator($iter1);
		// arrays should never be more than 2 deep
		$iter2->setMaxDepth(2);

		// re:REWIND ... http://stackoverflow.com/questions/13555884/recursiveiteratoriterator-returns-extra-elements
		for($iter2->rewind(); $iter2->valid(); $iter2->next()){
			$iter3 = $iter2->getInnerIterator();
			foreach($iter3 as $key => $value){
				if(is_null($value)) continue; // null values must be array(true, 'null')
				$columns[$key] = $key;
				if( is_array($value) && (true === $value[0]) ){
					$tokens[$key] = $value[1];
				}else{
					if( $this->namedTokens && (strpos($val, ":") === 0) ){
						$tokens[$key]  = $value;
					}else{
						$tokens[$key]  = "?";
					}
				}
			}
		}

		// strip out the keys we used for uniqueness as they get in the way later
		return array(array_values($columns), array_values($tokens));
	}
}