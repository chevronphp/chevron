<?php

include_once( __DIR__ . "/autoload.php");

define('IS_CLI', substr(strtolower(php_sapi_name()), 0, 3) == 'cli');

date_default_timezone_set('UTC');
// date_default_timezone_set('America/Chicago');

if( function_exists('mb_internal_encoding') ) {
	mb_internal_encoding('UTF-8');
}

// error_reporting(E_ALL ^ E_NOTICE);
set_exception_handler(array('\Chevron\Errors\ExceptionHandler','handler'));
set_error_handler(array('\Chevron\Errors\ErrorHandler','handler'));

Chevron\Misc\Loader::loadConstants("WeightsAndMeasures");
Chevron\Misc\Loader::loadFunctions("debug_functions");

# http://us3.php.net/manual/en/ini.list.php
// ini_set("short_open_tag", true);
// ini_set("display_errors", true);
// ini_set("log_errors", false);
// ini_set("default_charset", "UTF-8");
// ini_set("mbstring.internal_encoding", "UTF-8");
// ini_set("memory_limit", "512M");
// ini_set("post_max_size", "12M");
// ini_set("upload_max_filesize", "12M");
// ini_set("max_execution_time", "200");
// ini_set("auto_detect_line_endings", true);
// ini_set("date.timezone", "UTC");

// ini_set("session.name", "PHPSESSID");
// ini_set("session.use_trans_sid", false);
// ini_set("session.save_handler", "memcached");
// ini_set("session.save_path", "127.0.0.1:11211");
// ini_set("session.gc_divisor", 100);
// ini_set("session.gc_probability", 1);
// ini_set("session.gc_lifetime", 1440);
