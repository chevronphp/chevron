<?php

namespace Chevron\Autoloader;

class BaseAutoloader {

	/**
	 * var to store the top level dirs to scan, set at instantiation
	 * this is only relevant to the default, but is here to allow dev
	 * access to the list of dirs scanned
	 */
	public $dirs;

	/**
	 * constructor method to set the default top level dirs to scan
	 * and register the default autoloader. PSR aware
	 * @param array $dirs The top level dirs to scan
	 * @return
	 */
	function __construct(array $dirs){
		$this->dirs = $dirs;
		return $this->default_autoloader($dirs);
	}

	/**
	 * Method to register the default autoloader
	 * @param array $dirs The top level dirs to scan
	 * @return
	 */
	protected function default_autoloader(array $dirs){
		spl_autoload_register( function ( $class ) use ( $dirs ) {
			$path = strtr(trim($class, " \\"), "\\", "/");

			foreach( $dirs as $dir ) {
				$file = "{$dir}/{$path}.php";
				if( file_exists($file) ) {
					require($file);
					return;
				}
			}
		});
	}

	/**
	 * public method to register additional autoloader functions
	 * @param callable $callback The callback function to use
	 * @return
	 */
	function register(callable $callback){
		spl_autoload_register($callback);
	}

}
