<?php

namespace Chevron\HTTP\Exceptions;

class InvalidLayout extends \Exception {

	protected $code = 999;
	protected $message = "The layout you specified cannot be found.";

}