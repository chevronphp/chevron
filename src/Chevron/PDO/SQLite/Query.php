<?php

namespace Chevron\PDO\SQLite;
/**
 *
 * For documentation, consult the Interface (__DIR__ . "/WrapperInterface.php")
 *
 * @package DB
 */
class Query implements \Chevron\PDO\Interfaces\QueryInterface {

	/**
	 * I am NOT handling named tokens at this time.
	 */
	protected $namedTokens = false;
	protected $columns, $tokens;

	/**
	 * For documentation, consult the Interface (__DIR__ . "/QueryInterface.php")
	 */
	function __construct($tokens = false){
		if($tokens){
			$this->namedTokens = true;
		}
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/QueryInterface.php")
	 */
	function insert($table, array $map, $multiple){
		list($columns, $tokens) = $this->paren_pairs($map, $multiple);
		$_SQL   = sprintf("INSERT INTO %s %s VALUES %s;", $table, $columns, $tokens);
		return $_SQL;
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/QueryInterface.php")
	 */
	function replace($table, array $map, $multiple){
		list($columns, $tokens) = $this->paren_pairs($map, $multiple);
		$_SQL = sprintf("INSERT OR REPLACE INTO %s %s VALUES %s;", $table, $columns, $tokens);
		return $_SQL;
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/QueryInterface.php")
	 */
	function update($table, array $map, array $where){
		$column_map      = $this->equal_pairs($map, ", ");
		$conditional_map = $this->equal_pairs($where, " and ");
		$_SQL = sprintf("UPDATE %s SET %s WHERE %s;", $table, $column_map, $conditional_map);
		return $_SQL;
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/QueryInterface.php")
	 */
	function on_duplicate_key($table, array $map, array $where){
		$column_map      = $this->equal_pairs($map, ", ");
		$conditional_map = $this->equal_pairs($where, ", ");
		$_SQL = sprintf("INSERT INTO %s SET %s, %s ON DUPLICATE KEY UPDATE %s;", $table, $column_map, $conditional_map, $column_map);
		return $_SQL;
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/QueryInterface.php")
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
	 * For documentation, consult the Interface (__DIR__ . "/QueryInterface.php")
	 */
	function filter_data(){
		$final = array();
		$maps = func_get_args();
		foreach($maps as $map){
			foreach($map as $key => $value){
				if( is_scalar($value) ){
					$final[] = $value;
				}
			}
		}
		return $final;
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/QueryInterface.php")
	 */
	function filter_multi_data(array $rows){
		$final = array();
		foreach($rows as $row){
			$tmp = $this->filter_data($row);
			$final = array_merge($final, $tmp);
		}
		return $final;
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/QueryInterface.php")
	 */
	protected function paren_pairs(array $map, $multiple){
		$tmp = $this->map_columns($map);
		$columns = array_keys($tmp);
		$tokens  = array_values($tmp);

		$columns = sprintf("(`%s`)", implode("`, `", $columns));
		$tokens  = sprintf("(%s)",   implode(", ",   $tokens));

		if($multiple){
			$tokens = rtrim(str_repeat("{$tokens},", $multiple), ",");
		}

		return array( $columns, $tokens );
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/QueryInterface.php")
	 */
	protected function equal_pairs(array $map, $sep = ", "){
		$tmp = $this->map_columns($map);
		$columns = array_keys($tmp);
		$tokens  = array_values($tmp);

		$count = count($columns);
		for( $i = 0; $i < $count; ++$i ){
			$temp[] = "`{$columns[$i]}` = {$tokens[$i]}";
		}

		return implode($sep, $temp);
	}
	/**
	 * For documentation, consult the Interface (__DIR__ . "/QueryInterface.php")
	 */
	protected function map_columns(array $map){
		$columns = $tokens = array();
		foreach($map as $key => $value){
			if(is_array($value)){
				// check for bool switch
				if(array_key_exists(0, $value)){
					if($value[0] !== true) continue;

					$columns[$key] = $key;
					$tokens[$key]  = $value[1];

				}else{
					// if another array recurse
					$tmp = $this->map_columns($value);
					$columns = array_merge($columns, array_keys($tmp));
					$tokens  = array_merge($tokens, array_values($tmp));
				}
			}else{
				if(is_null($value)) continue;
				// catch non-null scalars
				$columns[$key] = $key;
				$tokens[$key]  = "?";
			}
		}
		// because $columns will inevitably contain duplicate values, once the
		// two arrays are combined, they will collapse/uniquify. #darkcorner
		return array_combine($columns, $tokens);
	}
}