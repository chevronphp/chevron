<?php

namespace Chevron\Misc;

class Loader {
	static function __callStatic($name, $args){
		if(isset($args[0])){
			$dir    = dirname(__FILE__);
			$subdir = preg_replace("|(.*)([A-Z]\w*)|", "\\2", $name);
			$file   = pathinfo($args[0], PATHINFO_FILENAME);
			if($subdir != ""){
				include_once("{$dir}/{$subdir}/{$file}.php");
			}
		}
	}
}