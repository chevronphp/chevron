<?php

namespace Chevron\HonkerBot;

class HonkerBot extends Commands {

	/**
	 * our connection
	 */
	protected $handle;

	/**
	 * keep a stack of events
	 */
	protected $events = array();

	/**
	 * send lines to STDERR?
	 */
	public $suppress = false;

	/**
	 * add a default PING/PONG to our bot
	 * @return
	 */
	function __construct(){
		$this->addEvent("|^PING :(?P<code>.*)$|i", function($matches){
			return sprintf("PONG :%s\n", $matches["code"]);
		});
	}

	/**
	 * connect to an IP:PORT
	 * @param string $ip The IP address
	 * @param string $port The PORT
	 * @return
	 */
	function connect( $ip, $port ){
		try {
			$errno = $errstr = "";
			$sock = "tcp://{$ip}:{$port}";
			$this->handle = stream_socket_client($sock, $errno, $errstr);
		} catch (Exception $e) {
			trigger_error($e->getMessage());
		}

		if( !$this->handle ){
			trigger_error("Connection Error: {$errno} / {$error}");
		}
	}
	/**
	 * write a string to the socket and to STDERR
	 * @param resource $handle The connection
	 * @param string $msg The string
	 * @return int
	 */
	function write( $handle, $msg ){
		$msg = rtrim( $msg, "\n" );
		$len = fwrite( $handle, "{$msg}\n" );
		if(!$this->suppress) fwrite( STDERR,  ">> {$msg}\n" );
		return $len;
	}

	/**
	 * send a line through each of our events
	 * @param string $line The line to match
	 * @return
	 */
	function hook( $line ){
		if(!is_array($this->events)) return;
		foreach($this->events as $k => $event){
			if(!is_callable($event["callback"])) continue;

			$response = false;
			if( preg_match($event["pattern"], $line, $matches) ){
				$response = call_user_func($event["callback"], $matches);
			}

			switch(true){
				case is_string($response) :
					$this->write($this->handle, $response);
				break;
				case is_null($response) :
					unset($this->events[$k]);
				break;
			}
		}
	}

	/**
	 * add an event. events are composed of a regex pattern to match and a
	 * callback. the callback is passed the array of matches from the regex.
	 * the callback should return the response string or NULL. if NULL the event
	 * will be removed from the events array. this is useful for events that
	 * ought to only fire once or after a certain number of calls.
	 *
	 * @param string $pattern The regex to use to match the lines coming through
	 * @param callable $callback The function to execute
	 * @return
	 */
	function addEvent( $pattern, $callback ){
		$this->events[] = array(
			"pattern"  => $pattern,
			"callback" => $callback,
		);
	}

	/**
	 * infinite loop over each line that comes from the server
	 * @return type
	 */
	function listen(){
		while( $line = trim( fgets( $this->handle ) ) ){
			stream_set_timeout( $this->handle, 3600 );

			if(!$this->suppress) fwrite(STDERR, "<< {$line}\n");

			$this->hook($line);
		}
	}

}