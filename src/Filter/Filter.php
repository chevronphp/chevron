<?php

namespace Chevron\Filter;

class Filter {
	/**
	 * Filter a mixed value translating spaces " " for dangerous control chars.
	 * This will recurse deeper into arrays
	 *
	 * @param array $map The value to sanitize
	 * @return mixed
	 */
	function arrayControlChars($map){
		array_walk_recursive($map, function(&$value){
			$value = strtr($value, "\x00\x07\x08\x09\x0B\x0C\x0D\x1A", "\x20\x20\x20\x20\x20\x20\x20\x20");
		});
		return $map;
	}
	/**
	 * Filter a mixed value translating spaces " " for dangerous control chars.
	 * This will recurse deeper into arrays
	 *
	 * @param mixed $value The value to sanitize
	 * @return mixed
	 */
	function scalarControlChars($value){
		return strtr($value, "\x00\x07\x08\x09\x0B\x0C\x0D\x1A", "\x20\x20\x20\x20\x20\x20\x20\x20");
	}
	/**
	 * function to take the global $_FILES and normalize it's structure to
	 * be the same for one field, multiple files, one field with multiple
	 * inputs, and multiple files with multiple inputs. there is NO
	 * validation. you should know roughly what you're doing when handling
	 * uploaded files. this function will not protect you in any way.
	 * @param array $globalFilesArray Ideally the $_FILES array
	 * @return array
	 */
	function normalizeGlobalFiles($array){
		$files = array();
		if(!$array) return $files;

		$fields = array_keys($array);
		foreach($fields as $field){
			$file = array();
			foreach($array[$field] as $label => $value){
				if(is_array($value)){
					foreach($value as $num => $val){
						$files[$field][$num][$label] = $val;
					}
				}else{
					$file[$label] = $value;
				}
			}
			if($file){
				$files[$field][] = $file;
			}
		}
		return $files;
	}
}