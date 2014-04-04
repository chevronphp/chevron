<?php


if(!function_exists("normalize_global_files_array")){
	/**
	 * function to take the global $_FILES and normalize it's structure to
	 * be the same for one field, multiple files, one field with multiple
	 * inputs, and multiple files with multiple inputs.
	 * @param array $globalFilesArray Ideally the $_FILES array
	 * @return array
	 */
	function normalize_global_files_array(array $globalFilesArray){

		$files = array();
		if(!$globalFilesArray) return $files;

		$fields = array_keys($globalFilesArray);
		foreach($fields as $field){
			$file = array();
			foreach($globalFilesArray[$field] as $label => $value){
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