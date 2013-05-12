<?php

namespace Chevron\Errors;

class ExceptionHandler {
	public static function printException(\Exception $e){
		$stream = fopen("php://memory", "rw");

		$file = $e->getFile();
		$file = basename($file);
		fwrite($stream, "\n\n### {$file}:{$e->getLine()} \n");
		fwrite($stream, str_repeat("#", 72));
		fwrite($stream, "\n\n{$e->getMessage()}");
		fwrite($stream, "\n\n");
		fwrite($stream, "\n\n### BackTrace \n");
		fwrite($stream, str_repeat("#", 72));
		// fwrite($stream, "\n\n{$e->getTraceAsString()}");
		$trace = $e->getTrace();
		foreach($trace as $i => $row){

			$class = "";
			if(isset($row["class"])){
				$class = "{$row['class']}";
			}

			if(isset($row["object"])){
				$class = "({$row["object"]}) {$class}";
			}

			$type = "";
			if(isset($row["type"])){
				$type = "{$class}{$row['type']}";
			}

			$function = "";
			if(isset($row["function"])){
				$function = "{$type}{$row['function']}";
			}

			$file = "";
			if(isset($row["file"])){
				$file = basename($row["file"]);
			}

			$line = "";
			if(isset($row["line"])){
				$line = $row["line"];
			}

			// we skip args on errors

			$num = $i += 1;
			fwrite($stream, "\n\n{$num}) {$file}:{$line} ... {$function}()");

		}

		if(0 === stripos(php_sapi_name(), "cli")){
			fwrite(STDOUT, stream_get_contents($stream, -1, 0));
		}else{
			printf("<pre>%s</pre>", stream_get_contents($stream, -1, 0));
		}

		$code = $e->getCode();
		exit($code);
	}

	public static function handler(\Exception $e){
		 self::printException($e);
	}
}