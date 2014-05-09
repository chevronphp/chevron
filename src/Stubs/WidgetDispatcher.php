<?php

namespace Chevron\Stubs;
/**
 * dispatches Widgets based on a given source __DIR__
 * @package Chevron\Stubs
 */
class WidgetDispatcher {
	/**
	 * The source dir
	 */
	protected $sourceDir;

	/**
	 * Set the base directory from which to load Widgets
	 * @param string $dir The source dir
	 * @return
	 */
	function __construct($dir){
		if( !is_dir($dir) ){
			throw new \Exception("WidgetFactory::__construct requires a valid source directory.");
		}

		$this->sourceDir = trim($dir);
	}

	/**
	 * dispatcher method for Widgets
	 * @param string $file The file to load
	 * @param array $data The data to pass to the Widget
	 * @return Chevron\Stubs\Widget
	 */
	function make($file, array $data = array()){
		$file = sprintf("%s/%s", rtrim($this->sourceDir, DIRECTORY_SEPERATOR), ltrim($file, DIRECTORY_SEPERATOR) );
		return new Widget($file, $data);
	}

}
