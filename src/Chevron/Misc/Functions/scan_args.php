<?php
/**
 * function to parse the arguments sent to a CLI script
 * @param array $values The CLI args to assume have values
 * @param array $flags The CLI args to assume do NOT have values
 * @return array
 */
if( !function_exists("scan_args") ){
	function scan_args(array $args, array $values, array $flags = array()){

		// $values = array_fill_keys($values, false);
		$final  = array_fill_keys($flags, false);

		$_argv = array_reverse($args);

		$self = array_pop($_argv);

		while( $arg = array_pop($_argv) ){
			$arg = trim($arg, " -");
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