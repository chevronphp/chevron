<?php
/**
 * !! via http://us3.php.net/manual/en/session.customhandler.php
 * !! via http://us3.php.net/manual/en/class.sessionhandler.php
 *
 * When the session starts, PHP will internally call the open handler followed by the
 * read callback which should return an encoded string extactly as it
 * was originally passed for storage. Once the read callback returns the encoded string,
 * PHP will decode it and then populate the resulting array into the $_SESSION superglobal.
 *
 * When PHP shuts down (or when session_write_close() is called), PHP will internally encode
 * the $_SESSION superglobal and pass this along with the session ID to the the write
 * callback. After the write callback has finished, PHP will internally invoke the close
 * callback handler.
 *
 * When a session is specifically destroyed, PHP will call the destroy handler with the
 * session ID.
 *
 * PHP will call the gc callback from time to time to expire any session records according
 * to the set max lifetime of a session. This routine should delete all records from
 * persistent storage which were last accessed longer than the $lifetime.
 *
 * // implements SessionHandlerInterface
 */

class CustomSessionHandler {
	private $savePath;
	private $session_prefix = "sess_";
	private $session_time;
	/**
	 * Method to append a custom session id to all session names
	 * @param string $id The session id
	 * @return string
	 */
	function session_name( $id ){ return sprintf( "%s%s", $this->session_prefix, $id ); }

	public function open( $savePath, $sessionName ){
		$this->session_time = ( ONE_MINUTE * 15 );

		//this bit of code is necessary for when using a class for a session handler due to the way PHP juggles __desturctors and sessions ...
		register_shutdown_function('session_write_close');

		return true;
	}

	public function close(){
		return true;
	}

	public function read( $id ){
		return "get a value, reset expiration";
	}

	public function write( $id, $data ){
		return "set a value, set expiration";
	}

	public function destroy( $id ){
		return "delete a value";
	}

	public function gc( $maxlifetime ){
		return true;
	}
}
