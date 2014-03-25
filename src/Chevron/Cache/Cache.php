<?php

namespace Chevron\Cache;

class Cache {

	protected $io;
	public  $expire;

	function __construct(Drivers\DriverInterface $io, $defaultExpire){
		$this->io = $io;
		$this->expire = $defaultExpire;
	}

	/**
	 * For documentation, consult the Interface
	 */
	function get($key){
		return $this->io->get($key);
	}
	/**
	 * For documentation, consult the Interface
	 */
	function set($key, $value, $expire = false){
		$expire = $expire ?: $this->expire;
		return $this->io->set($key, $value, $expire);
	}
	/**
	 * For documentation, consult the Interface
	 */
	function success(){
		return $this->io->success();
	}
	/**
	 * For documentation, consult the Interface
	 */
	function make_key( $key, $hash = "sha1" ){
		return $this->io->make_key($key, $hash);
	}

	function __call($name, $args){
		if(method_exists($this->io, $name)){
			return call_user_func_array(array($this->io, $name), $args);
		}
	}

}