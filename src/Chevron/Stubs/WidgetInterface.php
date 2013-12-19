<?php

namespace Chevron\Stubs;

interface WidgetInterface {

	function loadData(array $data);

	function render();

	function setMeta(array $data);

	function getMeta($key);

}