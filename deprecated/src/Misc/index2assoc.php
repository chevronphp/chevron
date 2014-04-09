<?php


function index2assoc( $array ){
	while( $key = array_shift( $array ) ){
		$final[ $key ] = array_shift( $array );
	}
	return $final;
}
