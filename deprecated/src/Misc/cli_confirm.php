<?php


function cli_confirm($confirm = "Really?", $yes = "y", $no = "n"){
	fwrite( STDOUT, "{$confirm} ({$yes}/{$no}) ");
	if( trim(fgets(STDIN)) === $yes ){
		return true;
	}
	return false;
}
