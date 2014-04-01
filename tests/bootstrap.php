<?php

require_once "vendor/autoload.php";

spl_autoload_register(function($class){
	$class = strtr($class, "\\", "/");
	if( file_exists( "src/{$class}.php" ) ){
		require "src/{$class}.php";
	}
});