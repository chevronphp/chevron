<?php

class HonkerBotTest extends PHPUnit_Framework_TestCase {

	public function test_construct(){

		$bot = new \Chevron\HonkerBot\HonkerBot;
		$bot->suppress = true;
		$reflection = new ReflectionClass($bot);
		$events = $reflection->getProperty("events");
		$events->setAccessible(true);
		$events = $events->getValue($bot);

		$this->assertEquals(1, count($events), "HonkerBot::__construct failed to set default event");

	}

	function test_write(){
		$bot = new \Chevron\HonkerBot\HonkerBot;
		$bot->suppress = true;
		$handle = fopen("php://memory", "rw+");
		$text = "This is some sample text.";
		$bot->write($handle, $text, true);

		rewind($handle);
		$result = fread($handle, strlen($text));
		$this->assertEquals($text, $result, "HonkerBot::write failed to write");

	}

	/**
	 * @depends test_construct
	 */
	function test_hook_string(){
		$bot = new \Chevron\HonkerBot\HonkerBot;
		$bot->suppress = true;
		$reflection = new ReflectionClass($bot);
		$handle = fopen("php://memory", "rw+");
		$botHandle = $reflection->getProperty("handle");
		$botHandle->setAccessible(true);
		$botHandle->setValue($bot, $handle);

		$toParse = "PING :irc.honkerbot.com";

		$bot->hook($toParse);

		rewind($handle);
		$result = fread($handle, strlen($toParse));
		$expected = "PONG :irc.honkerbot.com";
		$this->assertEquals($expected, $result, "HonkerBot::hook (string) failed to write");

	}

	function test_add_event(){

		$bot = new \Chevron\HonkerBot\HonkerBot;
		$bot->suppress = true;
		$reflection = new ReflectionClass($bot);
		$events = $reflection->getProperty("events");
		$events->setAccessible(true);

		$bot->addEvent("|^PING :(?P<code>.*)$|i", function($matches){
			return "a string";
		});

		$events = $events->getValue($bot);

		$this->assertEquals(2, count($events), "HonkerBot::addEvent failed to set a second event");

	}

	/**
	 * @depends test_add_event
	 */
	function test_hook_null(){
		$bot = new \Chevron\HonkerBot\HonkerBot;
		$bot->suppress = true;
		$reflection = new ReflectionClass($bot);
		$events = $reflection->getProperty("events");
		$events->setAccessible(true);
		$handle = fopen("php://memory", "rw+");
		$botHandle = $reflection->getProperty("handle");
		$botHandle->setAccessible(true);
		$botHandle->setValue($bot, $handle);

		$bot->addEvent("|^PING :(?P<code>.*)$|i", function($matches){
			return null;
		});

		$toParse = "PING :irc.honkerbot.com";

		$bot->hook($toParse);

		$events = $events->getValue($bot);

		$this->assertEquals(1, count($events), "HonkerBot::hook (null) failed to remove a null event");

	}

}