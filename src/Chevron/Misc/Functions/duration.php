<?php

if( !function_exists("duration") ){
	function duration( $start, $end ){

		$seconds = (int)$end - (int)$start;

		$mins = ($seconds / 60);
		if(!is_int($mins)){
			$mins = floor($mins);
			$secs = ($seconds % 60);
		}


		if($mins > 60){
			$hours = ($mins / 60);
			if(!is_int($hours)){
				$hours = floor($hours);
				$mins = $mins % 60;
			}
		}

		return sprintf("%02s:%02s:%02s", $hours, $mins, $secs);

	}
}