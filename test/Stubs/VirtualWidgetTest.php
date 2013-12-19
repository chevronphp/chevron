<?php

class VirtualWidgetTest extends PHPUnit_Framework_TestCase {

	public function test_render() {
		$widget = new \Chevron\Stubs\VirtualWidget(function(){
			return 999;
		});

		$expected = 999;
		$value = $widget->render();

		$this->assertEquals($expected, $value, "Stubs\VirtualWidget::render failed to return the result of the callback");
	}

	public function test___toString() {
		$widget = new \Chevron\Stubs\VirtualWidget(function( $obj ){
			print($obj->placebo);
		}, ["placebo" => "effect"]);

		$expected = "effect";
		$value = (string)$widget;

		$this->assertEquals($expected, $value, "Stubs\Widget::__toString failed to catch the print in an output buffer and return it");
	}

}