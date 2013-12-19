<?php

namespace Chevron\PDO\Interfaces;
/**
 * An interface defining the functionality of the Chevron\PDO\MySQL\Query class
 *
 * @package Chevron\PDO\MySQL
 * @author Jon Henderson
 */
Interface QueryInterface {
	/**
	 * Flag if we're accepting named tokens
	 */
	// protected $namedTokens = false;
	/**
	 * Return a new DBQ object
	 * @param bool $tokens A flag to accept named tokens
	 * @return Object
	 */
	function __construct($tokens = false);
	/**
	 * Format and return a valid SQL INSERT query for use in a prepared
	 * statement.
	 *
	 * @param string $table The table name
	 * @param array $map An array of column => value pairs
	 * @param int $multiple The number of rows to be inserted
	 * @return string
	 */
	function insert($table, array $map, $multiple);
	/**
	 * Format and return a valid SQL REPLACE query for use in a prepared
	 * statement.
	 *
	 * @param string $table The table name
	 * @param array $map An array of column => value pairs
	 * @param int $multiple The number of rows to be inserted
	 * @return string
	 */
	function replace($table, array $map, $multiple);
	/**
	 * Format and return a valid SQL UPDATE query for use in a prepared
	 * statement.
	 *
	 * @param string $table The table name
	 * @param array $map An array of column => value pairs
	 * @param array $where An array of column => value pairs
	 * @return string
	 */
	function update($table, array $map, array $where);
	/**
	 * Format and return a valid SQL INSERT ... ON DUPLICATE KEY UPDATE query
	 * for use in a prepared statement.
	 *
	 * @param string $table The table name
	 * @param array $map An array of column => value pairs
	 * @param array $where An array of column => value pairs
	 * @return string
	 */
	function on_duplicate_key($table, array $map, array $where);
	/**
	 * Parse a query replacing %s tokens with a string of ? tokens. Used for
	 * queries that use WHERE IN() clauses.
	 *
	 * @param string $query The query to parse
	 * @param array $map An array of column => value / column => array(values)
	 * 	to use in replacing %s tokens in the query
	 * @return array($query string, $data array)
	 */
	function in($query, array $map);
	/**
	 * Parse and flatten an array of column => value pairs into an array of
	 * values. This is restricted to a max depth of 2 so that
	 * array(true, "func") can still be used to pass unescaped values
	 *
	 * @return array
	 */
	function filter_data();
	/**
	 * Parse an array of column => value pairs into an array of column => token
	 * pairs in the SQL parenthetical format (e.g. (col, col) (?, ?)). If
	 * $multiple is present it will mulitply the token value to allow for
	 * multiple rows
	 *
	 * @param array $map The data to parse
	 * @param int $multiple The number of token strings to return
	 * @return array( $columns string, $tokens string )
	 */
	// protected function paren_pairs(array $map, $multiple);
	/**
	 * Parse an array of column => value pairs into an array of column => token
	 * pairs in the SQL equals format (e.g. col=? col=?). If $sep is provided
	 * $sep will be used to join the *=* pairs. This is useful in using commas
	 * in the column=>value area and `AND` in the where clause.
	 *
	 * @param array $map The data to parse
	 * @param string $sep The string to use in joining the pairs
	 * @return array( $columns string, $tokens string )
	 */
	// protected function equal_pairs(array $map, $sep = ", ");
	/**
	 * Parse an array of column => value pairs into column => token pairs
	 * allowing for the array(true, "func") syntax. Currently the named token
	 * parsing is experiemental.
	 *
	 * @param array $map The data to parse
	 * @return array( $columns array, $tokens array )
	 */
	// protected function map_columns(array $map);
}