<?php

if( !function_exists("simple_scan_args") ){
	/**
	 * Function to take an array, like a CLI command and parse it into meaningful
	 * key=>value pairs. Args should follow these formats:
	 *  -f
	 *  -flag
	 *  -key=value
	 *  -key value
	 *
	 * @param array $args The array to parse
	 * @param array $values The keys that SHOULD have a value
	 * @param array $flags The keys that should NOT have a value ... booleans
	 * @return array
	 */
	function simple_scan_args(array $args, array $values, array $flags = array()){

		// $values = array_fill_keys($values, false);
		$final = array_fill_keys($flags, false);
		$_argv = array_reverse($args);

		while( $arg = array_pop($_argv) ){
			$arg = trim($arg, " -");

			if(false !== ($pos = strpos($arg, "="))){
				$_argv[] = substr($arg, ($pos + 1));
				$arg     = substr($arg, 0, $pos);
			}

			switch(true){
				case in_array($arg, $values) :
					$final[$arg] = array_pop($_argv);
				break;
				case in_array($arg, $flags) :
					$final[$arg] = true;
				break;
			}
		}

		return $final;

	}
}