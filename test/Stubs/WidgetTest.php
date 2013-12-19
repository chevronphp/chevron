<?php

class WidgetTest extends PHPUnit_Framework_TestCase {

	public function test__construct_file() {
		$widget = new \Chevron\Stubs\Widget(__FILE__);

		$reflection = new ReflectionClass($widget);
		$property = $reflection->getProperty("file");
		$property->setAccessible(true);

		$expected = __FILE__;
		$value = $property->getValue($widget);

		$this->assertEquals($expected, $value, "Stubs\Widget::__construct failed to set the file property");
	}

	public function test__construct_data() {
		$widget = new \Chevron\Stubs\Widget(__FILE__, ["test" => "data"]);

		$reflection = new ReflectionClass($widget);
		$property = $reflection->getProperty("__map");
		$property->setAccessible(true);

		$expected = array("test" => "data");
		$value = $property->getValue($widget);

		$this->assertEquals($expected, $value, "Stubs\Widget::__construct failed to set the __map[] property");
	}

	public function test__construct_metaData() {
		$widget = new \Chevron\Stubs\Widget(__FILE__, ["test" => "data"], ["test2" => "data2"]);

		$reflection = new ReflectionClass($widget);
		$property = $reflection->getProperty("__meta");
		$property->setAccessible(true);

		$expected = array("test2" => "data2");
		$value = $property->getValue($widget);

		$this->assertEquals($expected, $value, "Stubs\Widget::__construct failed to set the __meta[] property");
	}

	public function test_loadData() {
		$widget = new \Chevron\Stubs\Widget(__FILE__);

		$widget->loadData(["test" => "data"]);

		$reflection = new ReflectionClass($widget);
		$property = $reflection->getProperty("__map");
		$property->setAccessible(true);

		$expected = array("test" => "data");
		$value = $property->getValue($widget);

		$this->assertEquals($expected, $value, "Stubs\Widget::loadData failed to set the __map[] property");
	}

	public function test_setMeta() {
		$widget = new \Chevron\Stubs\Widget(__FILE__);

		$widget->setMeta(["test" => "data"]);

		$reflection = new ReflectionClass($widget);
		$property = $reflection->getProperty("__meta");
		$property->setAccessible(true);

		$expected = array("test" => "data");
		$value = $property->getValue($widget);

		$this->assertEquals($expected, $value, "Stubs\Widget::loadData failed to set the __map[] property");
	}

	public function test___get() {
		$widget = new \Chevron\Stubs\Widget(__FILE__, ["test" => "data"]);

		$expected = "data";
		$value = $widget->test;

		$this->assertEquals($expected, $value, "Stubs\Widget::__get failed to get the __map[] property");
	}

	public function test___set() {
		$widget = new \Chevron\Stubs\Widget(__FILE__);

		$expected     = "data";
		$widget->test = "data";
		$value        = $widget->test;

		$this->assertEquals($expected, $value, "Stubs\Widget::__get failed to get the __map[] property");
	}

	public function test_getMeta() {
		$widget = new \Chevron\Stubs\Widget(__FILE__, ["test" => "data"], ["test2" => "data2"]);

		$expected = "data2";
		$value = $widget->getMeta("test2");

		$this->assertEquals($expected, $value, "Stubs\Widget::getMeta failed to get the __meta[] property");
	}

}