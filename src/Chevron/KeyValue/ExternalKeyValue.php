<?php

namespace Chevron\Objects;

class RedisOnlineWriter extends \Chevron\Redis\Redis implements \Chevron\Interfaces\CurrentlyOnlineWriterInterface {

	protected $keyspace;

	function __construct($keyspace){
		$this->keyspace = $keyspace
	}

	function set($key, $value){
		return $this->zadd($this->keyspace, $value, $key);
	}

	function delRange($min, $max){
		$this->zremrangebyscore($this->table, "-inf", "({$max}");
	}

	function len(){
		$this->zcard($this->table);
	}

	// function setMany(array $map){
	// 	foreach($map as $key => $value){
	// 		$this->set($key, $value);
	// 	}
	// }

	// function get($key){
	// 	return $this->zscore($this->keyspace, $key);
	// }

	// function getMany(array $map){
	// 	$final = array();
	// 	foreach($map as $value){
	// 		$final[$value] = $this->get($value);
	// 	}
	// 	return $final;
	// }

	// function getRange($min, $max){
	// 	return null;
	// }

	// function del(){}

	// function delMany(){}

}