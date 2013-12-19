<?php

namespace Chevron\HTTP\Routers;

use \Chevron\HTTP\Requests\CurrentRequest;

/**
 * This class is a VERY simple view processor. Any controller files at the
 * dir or file level should be coded into a better more advanced router.
 */
class BaseRouter {

	protected $options;

	/**
	 * method to construct a router based on a set of options
	 * @param \Chevron\HTTP\Routers\RouterOptions $options The options to use
	 * @return
	 */
	function __construct(array $dirs){
		$this->dirs = $dirs;
	}

	function getStack(CurrentRequest $request){
		$pre_files = $post_files = array();

		if($this->preStack){
			foreach($this->preStack as $callback){
				$pre_files[] = call_user_func($callback, $request);
			}
		}

		if($this->postStack){
			foreach($this->postStack as $callback){
				$post_files[] = call_user_func($callback, $request);
			}
		}

		if($pre_files){
			$pre_files  = $this->check_is_file($pre_files);
		}

		if($post_files){
			$post_files = $this->check_is_file($post_files);
		}

		return array_merge($pre_files, $post_files);
	}

	/**
	 * method to verify that the requested file(s) are actually a file(s)
	 * @param array $files The list of files to be found
	 * @return array
	 */
	protected function check_is_file(array $files){
		$iter1 = new \RecursiveArrayIterator($files);
		$iter2 = new \RecursiveIteratorIterator($iter1);

		$final = array();
		for($iter2->rewind(); $iter2->valid(); $iter2->next()){
			$dirs_to_scan = $this->dirs;
			foreach($dirs_to_scan as $dir){
				$filename = "{$dir}/{$iter2->current()}";
				if(is_file($filename)){
					$final[] = $filename;
				}
			}
		}

		if($final){
			$final = array_unique($final);
		}

		return $final;
	}

	/**
	 * register a function to return one or more files to include BEFORE
	 * the request is processed
	 * @param callable $callback The function
	 * @return mixed
	 */
	function register_pre(callable $callback){
		$this->preStack[] = $callback;
	}

	/**
	 * register a function to return one or more files to include AFTER
	 * the request is processed
	 * @param callable $callback The function
	 * @return mixed
	 */
	function register_post(callable $callback){
		$this->postStack[] = $callback;
	}

}
