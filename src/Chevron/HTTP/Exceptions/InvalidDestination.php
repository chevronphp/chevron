<?php

namespace Chevron\HTTP\Exceptions;

class InvalidDestination extends \Exception {

	protected $code = 999;
	protected $message = "The file you specified cannot be found.";

}