<?php

namespace Chevron\Errors;

class ErrorHandler {
	public static function handler($errno, $errstr, $errfile, $errline){
	    throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
	}
}