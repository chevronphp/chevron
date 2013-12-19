<?php

namespace Chevron\Memcached;
/**
 * This is a simple wrapper for the Memcached extension. It adds two helper
 * methods and a default expiration.
 *
 * To launch the memcached daemon:
 *   memcached -d -m 1024 -u root -l 127.0.0.1 -p 11211
 *
 * More information:
 *   http://code.google.com/p/memcached/wiki/NewServerMaint
 *
 */
class Memcached extends \Memcached implements \Chevron\Cache\CacheInterface {
	public $expire = 300;
	/**
	 * For documentation, consult the Interface
	 */
	function success(){
		return $this->getResultCode() === \Memcached::RES_SUCCESS;
	}
	/**
	 * For documentation, consult the Interface
	 */
	function set_default($key, $value){
		$this->set($key, $value, $this->expire);
	}
	/**
	 * For documentation, consult the Interface
	 */
	function make_key( $key, $hash = "md5" ){
		$key = !is_scalar( $key ) ? json_encode( $key, JSON_NUMERIC_CHECK ) : $key;
		return hash($hash, $key);
	}
	/**
	 * For documentation, consult the Interface
	 */
	function last_result( $str = false ){
		return sprintf( "%s ... %s", $this->getResultCode(), $this->getResultMessage());
	}
	/**
	 * Use the older memcache extension, if it exists, to get Extended info
	 * from the memcache cluster
	 */
	function get_extended_stats(){
		if( class_exists("\Memcache") ){
			$mem = new \Memcache;
			$servers = $this->getServerList();
			foreach($servers as $server){
				$mem->addServer($server["host"], $server["port"]);
			}
			return $mem->getExtendedStats();
		}
		return array();
	}
}


