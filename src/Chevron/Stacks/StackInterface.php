<?php

namespace Chevron\Stacks;

interface StackInterface {

	function add($value);

	function pop();

	function length();

}