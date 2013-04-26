<?php

namespace Chevron\Redis;

abstract class Protocol {
	/**
	 * Constant line ending according to Redis protocol
	 */
	const DELIM = "\r\n";
	/***/
	const PACKSIZE = 1024;
	/***/
	private $handle;
	/***/
	function __construct($host, $port){
		try {
			$this->handle = fsockopen($host, $port, $errno, $error);
		} catch (Exception $e) {
			trigger_error($e->getMessage());
		}

		if( !$this->handle ){
			trigger_error("Connection Error: {$errno} / {$error}");
		}
	}
	/**
	 * Method to write a command to our Redis connection and parse the response
	 * @param string $str The command to write
	 * @param int $count The number of expected responses 1:1 with function calls
	 * @return string|array
	 */
	protected function write( $str, $count = 1 ){

		$write = fwrite( $this->handle, $str );

		while( $count ){
			$response[] = $this->parse();
			--$count;
		}

		return count( $response ) == 1 ? current( $response ) : $response;
	}
	/**
	 * Method to parse a Redis response string
	 * @return sring|array
	 */
	protected function parse( ){
		$line = trim( fgets( $this->handle ) );

		switch( substr( $line, 0, 1 ) ){
			case( "+" ): //single line
			case( ":" ): //integer
				$response = substr( trim( $line ), 1 );
			break;
			case( "-" ): //error
				trigger_error( $line, E_USER_WARNING );
			break;
			case( '$' ): //bulk
				if( strpos( $line, "-1" ) === 1 ){ return null; }

				$count = substr( $line, 1 );
				return $this->parse_bulk( $count );
			break;
			case( "*" ): //multi-bulk

				$count = substr( $line, 1 );
				if( $count == 0 ) return 0;

				while( $count ){
					$response[] = $this->parse();
					--$count;
				}
				return $response;
			break;
		}

		return $response;

	}
	/**
	 * Method to read a value out of a parsed Redis response
	 * @param int $size The size of the value
	 * @return string
	 */
	private function parse_bulk( $size ){

		if($size == '-1') return null;

		$response = "";

		while( $size ){
			$chunk = $size > self::PACKSIZE ? self::PACKSIZE : $size;
			$response .= fread( $this->handle, $chunk );
			$size -= $chunk;
		}

		fread($this->handle, 2);

		return $response;
	}
	/**
	 * Method to send multiple commands in one round trip
	 *
	 * @param array A variable number of arrays the values of which are used to create the redis command OR an array of arrays/strings
	 * @return array
	 */
	function pipe(){

		$iter1 = new \RecursiveArrayIterator(func_get_args());
		$iter2 = new \RecursiveIteratorIterator($iter1);
		// arrays should never be more than 2 deep
		$iter2->setMaxDepth(2);

		// re:REWIND ... http://stackoverflow.com/questions/13555884/recursiveiteratoriterator-returns-extra-elements
		for($iter2->rewind(); $iter2->valid(); $iter2->next()){
			$command = "";
			$i       = 0;
			$iter3   = $iter2->getInnerIterator();
			for($iter3->rewind(); $iter3->valid(); $iter3->next()){
				++$i;
				$command .= "$" . strlen($iter3->current());
				$command .= static::DELIM;
				$command .= $iter3->current();
				$command .= static::DELIM;
			}
			$funcs[] = sprintf("*%d%s%s", $i, static::DELIM, $command);
		}

		$command = implode(static::DELIM, $funcs) . static::DELIM;

		return $this->write( $command, count( $funcs ) );

	}
}


