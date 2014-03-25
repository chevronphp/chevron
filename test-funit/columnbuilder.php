<?php

// array(
// 	"col" => "val"
// );

// array(
// 	"col" => array(true, "val")
// );

// array(
// 	0 => array(
// 		"col" => "val",
// 		col => array(true, "val"),
// 	),
// 	1 => array(
// 		"col" => "val"
// 	),
// )

require "vendor/autoload.php";

$i = 1;

function OLDdive($map, &$cols){
	foreach($map as $key => $value){
		if(is_array($value)){
			if(array_key_exists(0, $value)){
				if($value[0] === true){
					$cols[$key] = $key;
				}
			}else{
				dive($value, $cols);
			}
		}else{
			$cols[$key] = $key;
		}
	}
}

function OLDdive2(array $map){
	$columns = $tokens = array();
	foreach($map as $key => $value){
		if(is_array($value)){
			if(array_key_exists(0, $value)){
				if($value[0] === true){
					$columns[$key] = $key;
					$tokens[$key]  = $value[1];
				}
			}else{
				list($tmpColumns, $tmpTokens) = dive($value);
				$columns = array_unique(array_merge($columns, $tmpColumns));
				$tokens  = array_unique(array_merge($tokens, $tmpTokens));
			}
		}else{
			$columns[$key] = $key;
			$tokens[$key]  = "?";
		}
	}
	return array(array_values($columns), array_values($tokens));
}

function dive(array $map){
	$columns = $tokens = array();
	foreach($map as $key => $value){
		if(is_array($value)){
			// check for bool switch
			if(array_key_exists(0, $value)){
				if($value[0] !== true) continue;

				$columns[$key] = $key;
				$tokens[$key]  = $value[1];

			}else{
				// if another array recurse
				$tmp = dive($value);
				$columns = array_merge($columns, array_keys($tmp));
				$tokens  = array_merge($tokens, array_values($tmp));
			}
		}else{
			if(is_null($value)) continue;
			// catch non-null scalars
			$columns[$key] = $key;
			$tokens[$key]  = "?";
		}
	}
	// because $columns will inevitably contain duplicate values, once the
	// two arrays are combined, they will collapse/uniquify. #darkcorner
	return array_combine($columns, $tokens);
}

FUnit::test(sprintf("%s %s", "Test", $i++), function(){

	$a = array(
		"col1" => "val",
		"col2" => "val",
		"col3" => "val",
		"col4" => "val",
	);

	$columns = array(
		"col1",
		"col2",
		"col3",
		"col4",
	);

	$tokens = array(
		"?",
		"?",
		"?",
		"?",
	);

	// list($c, $t) = dive($a);
	$tmp = dive($a);
	$c = array_keys($tmp);
	$t = array_values($tmp);

	FUnit::equal($columns, $c, "testing columns for simple col => val");
	FUnit::equal($tokens, $t, "testing tokens for simple col => val");

});

FUnit::test(sprintf("%s %s", "Test", $i++), function(){

	$a = array(
		"col1" => "val",
		"col2" => null,
		"col3" => "val",
		"col4" => "val",
	);

	$columns = array(
		"col1",
		"col3",
		"col4",
	);

	$tokens = array(
		"?",
		"?",
		"?",
	);

	// list($c, $t) = dive($a);
	$tmp = dive($a);
	$c = array_keys($tmp);
	$t = array_values($tmp);

	FUnit::equal($columns, $c, "testing columns for simple col => val with a NULL value");
	FUnit::equal($tokens, $t, "testing tokens for simple col => val with a NULL value");

});

FUnit::test(sprintf("%s %s", "Test", $i++), function(){

	$a = array(
		"col1" => array(true, "val"),
		"col2" => array(true, "val"),
		"col3" => array(true, "val"),
		"col4" => array(true, "val"),
	);

	$columns = array(
		"col1",
		"col2",
		"col3",
		"col4",
	);

	$tokens = array(
		"val",
		"val",
		"val",
		"val",
	);

	// list($c, $t) = dive($a);
	$tmp = dive($a);
	$c = array_keys($tmp);
	$t = array_values($tmp);

	FUnit::equal($columns, $c, "testing columns for simple col => array(true, val)");
	FUnit::equal($tokens, $t, "testing tokens for simple col => array(true, val)");

});

FUnit::test(sprintf("%s %s", "Test", $i++), function(){

	$a = array(
		"col1" => "val",
		"col2" => "val",
		"col3" => array(true, "val"),
		"col4" => array(true, "val"),
	);

	$columns = array(
		"col1",
		"col2",
		"col3",
		"col4",
	);

	$tokens = array(
		"?",
		"?",
		"val",
		"val",
	);

	// list($c, $t) = dive($a);
	$tmp = dive($a);
	$c = array_keys($tmp);
	$t = array_values($tmp);

	FUnit::equal($columns, $c, "testing columns for mixed col => val, col => array(true, val) where arrays are last");
	FUnit::equal($tokens, $t, "testing tokens for mixed col => val, col => array(true, val) where arrays are last");

});

FUnit::test(sprintf("%s %s", "Test", $i++), function(){

	$a = array(
		"col1" => array(true, "val"),
		"col2" => array(true, "val"),
		"col3" => "val",
		"col4" => "val",
	);

	$columns = array(
		"col1",
		"col2",
		"col3",
		"col4",
	);

	$tokens = array(
		"val",
		"val",
		"?",
		"?",
	);

	// list($c, $t) = dive($a);
	$tmp = dive($a);
	$c = array_keys($tmp);
	$t = array_values($tmp);

	FUnit::equal($columns, $c, "testing columns for mixed col => val, col => array(true, val) where arrays are first");
	FUnit::equal($tokens, $t, "testing tokens for mixed col => val, col => array(true, val) where arrays are first");

});

FUnit::test(sprintf("%s %s", "Test", $i++), function(){

	$a = array(
		"col1" => "val",
		"col2" => array(true, "val"),
		"col3" => array(true, "val"),
		"col4" => "val",
	);

	$columns = array(
		"col1",
		"col2",
		"col3",
		"col4",
	);

	$tokens = array(
		"?",
		"val",
		"val",
		"?",
	);

	// list($c, $t) = dive($a);
	$tmp = dive($a);
	$c = array_keys($tmp);
	$t = array_values($tmp);

	FUnit::equal($columns, $c, "testing columns for mixed col => val, col => array(true, val) where arrays are in the middle");
	FUnit::equal($tokens, $t, "testing tokens for mixed col => val, col => array(true, val) where arrays are in the middle");

});

FUnit::test(sprintf("%s %s", "Test", $i++), function(){

	$a = array(
		array("col1" => "val"),
		array("col1" => "val"),
		array("col1" => "val"),
		array("col1" => "val"),
	);

	$columns = array(
		"col1",
	);

	$tokens = array(
		"?",
	);

	// list($c, $t) = dive($a);
	$tmp = dive($a);
	$c = array_keys($tmp);
	$t = array_values($tmp);

	FUnit::equal($columns, $c, "testing columns for multi array(col => val)");
	FUnit::equal($tokens, $t, "testing tokens for multi array(col => val)");

});

FUnit::test(sprintf("%s %s", "Test", $i++), function(){

	$a = array(
		array("col1" => array(true, "val")),
		array("col1" => array(true, "val")),
		array("col1" => array(true, "val")),
		array("col1" => array(true, "val")),
	);

	$columns = array(
		"col1",
	);

	$tokens = array(
		"val",
	);

	// list($c, $t) = dive($a);
	$tmp = dive($a);
	$c = array_keys($tmp);
	$t = array_values($tmp);

	FUnit::equal($columns, $c, "testing columns for multi array(col => array(true, val))");
	FUnit::equal($tokens, $t, "testing tokens for multi array(col => array(true, val))");

});

FUnit::test(sprintf("%s %s", "Test", $i++), function(){

	$a = array(
		array("col1" => array(true, "val"), "col2" => array(true, "val")),
		array("col1" => array(true, "val"), "col2" => array(true, "val")),
		array("col1" => array(true, "val"), "col2" => array(true, "val")),
		array("col1" => array(true, "val"), "col2" => array(true, "val")),
	);

	$columns = array(
		"col1",
		"col2",
	);

	$tokens = array(
		"val",
		"val",
	);

	// list($c, $t) = dive($a);
	$tmp = dive($a);
	$c = array_keys($tmp);
	$t = array_values($tmp);

	FUnit::equal($columns, $c, "testing columns for multi array(col => array(true, val), col => array(true, val))");
	FUnit::equal($tokens, $t, "testing tokens for multi array(col => array(true, val), col => array(true, val))");

});

FUnit::test(sprintf("%s %s", "Test", $i++), function(){

	$a = array(
		array("col1" => "val", "col2" => "val"),
		array("col1" => "val", "col2" => "val"),
		array("col1" => "val", "col2" => "val"),
		array("col1" => "val", "col2" => "val"),
	);

	$columns = array(
		"col1",
		"col2",
	);

	$tokens = array(
		"?",
		"?",
	);

	// list($c, $t) = dive($a);
	$tmp = dive($a);
	$c = array_keys($tmp);
	$t = array_values($tmp);

	FUnit::equal($columns, $c, "testing columns for multi array(col => val, col => val)");
	FUnit::equal($tokens, $t, "testing tokens for multi array(col => val, col => val)");

});

FUnit::test(sprintf("%s %s", "Test", $i++), function(){

	$a = array(
		array("col1" => "val", "col2" => null),
		array("col1" => "val", "col2" => null),
		array("col1" => "val", "col2" => null),
		array("col1" => "val", "col2" => null),
	);

	$columns = array(
		"col1",
	);

	$tokens = array(
		"?",
	);

	// list($c, $t) = dive($a);
	$tmp = dive($a);
	$c = array_keys($tmp);
	$t = array_values($tmp);

	FUnit::equal($columns, $c, "testing columns for multi array(col => val, col => val) with a NULL value");
	FUnit::equal($tokens, $t, "testing tokens for multi array(col => val, col => val) with a NULL value");

});

FUnit::test(sprintf("%s %s", "Test", $i++), function(){

	$a = array(
		array("col1" => "val", "col2" => array(true, "val")),
		array("col1" => "val", "col2" => array(true, "val")),
		array("col1" => "val", "col2" => array(true, "val")),
		array("col1" => "val", "col2" => array(true, "val")),
	);

	$columns = array(
		"col1",
		"col2",
	);

	$tokens = array(
		"?",
		"val",
	);

	// list($c, $t) = dive($a);
	$tmp = dive($a);
	$c = array_keys($tmp);
	$t = array_values($tmp);

	FUnit::equal($columns, $c, "testing columns for multi array(col => val, col => array(true, val)) where arrays are second");
	FUnit::equal($tokens, $t, "testing tokens for multi array(col => val, col => array(true, val)) where arrays are second");

});

FUnit::test(sprintf("%s %s", "Test", $i++), function(){

	$a = array(
		array("col1" => array(true, "val"), "col2" => "val"),
		array("col1" => array(true, "val"), "col2" => "val"),
		array("col1" => array(true, "val"), "col2" => "val"),
		array("col1" => array(true, "val"), "col2" => "val"),
	);

	$columns = array(
		"col1",
		"col2",
	);

	$tokens = array(
		"val",
		"?",
	);

	// list($c, $t) = dive($a);
	$tmp = dive($a);
	$c = array_keys($tmp);
	$t = array_values($tmp);

	FUnit::equal($columns, $c, "testing columns for multi array(col => val, col => array(true, val)) where arrays are first");
	FUnit::equal($tokens, $t, "testing tokens for multi array(col => val, col => array(true, val)) where arrays are first");

});
