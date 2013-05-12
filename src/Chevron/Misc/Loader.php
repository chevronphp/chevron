<?php

namespace Chevron\Misc;
/*

The Misc module is a place to put procedural functions and perhaps
some global constants. The idea being that there are perfectly good
reasons for using functions, constants, and procedural what nots but
that using a formal system like Composer, PSR-0, etc. would make the
include paths very long and brittle. The Misc module consists of a
Loader class that simply loads specific files from within the module.
This way, the Loader class is available via the autoloader (self
rolled or Composers) but will include arbitrary files from within the
Misc module. Basically, the Misc module is the place to put all the
procedural scraps to be used in an OOP framework. It's also a place for
non module classes. Basically anything that's NOT a module goes in Misc
and is then included via the Loader class using an autoloading scheme.

*/
class Loader {
	static function __callStatic($name, $args){
		if(isset($args[0])){
			$dir    = dirname(__FILE__);
			$length = strcspn($name, "ABCDEFGHIJKLMNOPQRSTUVWXYZ");
			$subdir = substr($name, $length);
			// $subdir = preg_replace("|(.*)([A-Z]\w*)|", "\\2", $name);
			$file   = pathinfo($args[0], PATHINFO_FILENAME);
			if($subdir != ""){
				include_once("{$dir}/{$subdir}/{$file}.php");
			}
		}
	}
}