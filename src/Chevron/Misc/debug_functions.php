<?php

namespace Chevron;

/**
 * function to parse a variable number of args into a more descriptive format and
 * display it
 * @return
 */
function drop(){

	$stream = fopen("php://memory", "rw");

	foreach(func_get_args() as $key => $arg){

		fwrite($stream, "\n\n");
		fwrite($stream, "### Arg No {$key}\n");
		fwrite($stream, str_repeat("#", 72) . "\n\n");

		switch(true){
			case is_null($arg) :
				fwrite($stream, print_r("(null)null", true));
			break;
			case false === $arg :
				fwrite($stream, print_r("(bool)false", true));
			break;
			case true === $arg :
				fwrite($stream, print_r("(bool)true", true));
			break;
			default:
				fwrite($stream, print_r($arg, true));
			break;
		}

		fwrite($stream, "\n\n");
	}

	if(IS_CLI){
		fwrite(STDOUT, stream_get_contents($stream, -1, 0));
	}else{
		printf("<pre>%s</pre>", stream_get_contents($stream, -1, 0));
	}
	exit;
}

/**
 * function to parse the arguments sent to a CLI script
 * @param array $values The CLI args to assume have values
 * @param array $flags The CLI args to assume do NOT have values
 * @return array
 */
function parse_cli_args(array $values, array $flags = array()){

	// $values = array_fill_keys($values, false);
	$final  = array_fill_keys($flags, false);

	$_argv = array_reverse($_SERVER["argv"]);

	$self = array_pop($_argv);

	while( $arg = array_pop($_argv) ){
		$arg = trim($arg, " -");
		// if( array_key_exists($arg, $values) ){
		if( in_array($arg, $values) ){
			$final[$arg] = array_pop($_argv);
		}else{
			$final[$arg] = true;
		}
	}

	return $final;

}