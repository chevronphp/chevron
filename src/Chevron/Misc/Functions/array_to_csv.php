<?php
/**
 * Converts an array of arrays to CSV using the first columns keys as a header
 *
 * Note: Dependant on the VariableStream class
 *
 * @param array $data
 * @param bool $download
 * @param string|bool filename to download as
 */
if(!function_exists("array_to_csv")){
	function array_to_csv($data, $download = true, $fname = false) {
		//stream_wrapper_register("var", "VariableStream");

		if( $download ) {
			if( !$fname ) {
				$fname = sprintf('download_%s', date("Ymd_his"));
			}

			//present data for download
			header(sprintf("Content-Disposition: attachment; filename=%s.csv", $fname));
			header("Pragma: public"); // required
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private", false); // required for certain browsers
			header("Content-Type: text/csv; charset=utf-8");

		}else{
			ob_start();
		}

		$csv = fopen("php://output", "r+");
		if( is_array($data) && count($data) ) {
			$cols = array_keys($data[0]);
			array_unshift($data, $cols);
			foreach ($data as $row) {
				fputcsv($csv, $row);
			}
		}

		if( !$download ) {
			$out2 = ob_get_contents();
			ob_end_clean();
			return $out2;
		}

		if( $download ) { die(); }

	}
}