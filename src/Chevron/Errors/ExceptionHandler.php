<?php

namespace Chevron\Errors;

class ExceptionHandler {
	public static function printException(\Exception $e){
		$stream = fopen("php://memory", "rw");

		// $file = substr($e->getFile(), strlen(__DIR__));
		$file = $e->getFile();
		fwrite($stream, "\n\n{$file}:{$e->getLine()}");
		fwrite($stream, "\n\n{$e->getMessage()}");
		fwrite($stream, "\n\n");
		fwrite($stream, "\n\n{$e->getTraceAsString()}");

		if(IS_CLI){
			fwrite(STDOUT, stream_get_contents($stream, -1, 0));
		}else{
			printf("<pre>%s</pre>", stream_get_contents($stream, -1, 0));
		}

		$code = $e->getCode();
		exit($code);
	}

	public static function handle(\Exception $e){
		 self::printException($e);
	}
}