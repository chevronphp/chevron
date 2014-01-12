<?php

require "vendor/autoload.php";

$dirs = array(__DIR__);

foreach($dirs as $dir){
	$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
	for($it->rewind(); $it->valid(); $it->next()) {

		if ($it->isDot()) { continue; }
		if (substr($it->getSubPathName(), 0, 1) == ".") { continue; }

		$file = $dir . "/" . $it->getSubPathName();

		// lets avoid a giant recursive black hole
		if($file == __FILE__){ continue; }

		require $file;
	}
}

$exit = FUnit::run();
exit($exit);

