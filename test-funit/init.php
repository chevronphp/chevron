<?php

// xdebug_start_code_coverage();

require "vendor/autoload.php";
require "src/Chevron/Autoloader/BaseAutoloader.php";

FUnit::test("", function(){
	FUnit::ok(1, "did this work");
});

// $exit = FUnit::run();
// exit($exit);

new Chevron\Autoloader\BaseAutoloader(array("src"));

$dirs = array(__DIR__);

foreach($dirs as $dir){
	$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
	for($it->rewind(); $it->valid(); $it->next()) {

		//skip dots
		if ($it->isDot()) { continue; }
		//skip hidden files
		if (substr($it->getSubPathName(), 0, 1) == ".") { continue; }

		// generate a full path
		$file = $dir . "/" . $it->getSubPathName();

		// lets avoid a giant recursive black hole
		if($file == __FILE__){ continue; }

		require $file;
	}
}

$exit = FUnit::run();

// $coverage = xdebug_get_code_coverage();

// fwrite(STDOUT, "\n\n");
// foreach($coverage as $file => $line){
// 	$handle = file($file);
// 	$total = count($handle);
// 	$covered = count($coverage[$file]);
// 	fwrite(STDOUT, "{$file} -- {$covered}:{$total}\n");
// }

exit($exit);


