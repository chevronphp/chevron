<?php

FUnit::setup(function(){
	$bot = new \Chevron\HonkerBot\HonkerBot;
	FUnit::fixture("BOT", $bot);
});

// FUnit::test(sprintf("%s: %s", $label, ++$count), function(){};

FUnit::test("HonkerBot::__construct() sets the PONG callback", function(){

	$bot = FUnit::fixture("BOT");

	$bot->suppress = true;
	$reflection = new ReflectionClass($bot);
	$events = $reflection->getProperty("events");
	$events->setAccessible(true);
	$events = $events->getValue($bot);

	FUnit::equal(1, count($events));

});

FUnit::test("HonkerBot::write()", function(){
	$bot = FUnit::fixture("BOT");

	$bot->suppress = true;
	$handle = fopen("php://memory", "rw+");
	$text = "This is some sample text.";
	$bot->write($handle, $text, true);

	rewind($handle);
	$result = fread($handle, strlen($text));
	FUnit::equal($text, $result);

});

/**
 * @depends test_construct
 */
FUnit::test("HonkerBot::hook() execution", function(){
	$bot = FUnit::fixture("BOT");

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
	FUnit::equal($expected, $result);

});

FUnit::test("HonkerBot::addEvent()", function(){

	$bot = FUnit::fixture("BOT");

	$bot->suppress = true;
	$reflection = new ReflectionClass($bot);
	$events = $reflection->getProperty("events");
	$events->setAccessible(true);

	$pattern = "|^PING :(?P<code>.*)$|i";

	$bot->addEvent($pattern, function($matches){
		return "a string";
	});

	$events = $events->getValue($bot);

	FUnit::equal(2, count($events[$pattern]));

});

/**
 * @depends test_add_event
 */
FUnit::test("HonkerBot::hook() removes hook on NULL return", function(){
	$bot = FUnit::fixture("BOT");

	$bot->suppress = true;
	$reflection = new ReflectionClass($bot);
	$events = $reflection->getProperty("events");
	$events->setAccessible(true);
	$handle = fopen("php://memory", "rw+");
	$botHandle = $reflection->getProperty("handle");
	$botHandle->setAccessible(true);
	$botHandle->setValue($bot, $handle);

	$pattern = "|^PING :(?P<code>.*)$|i";

	$bot->addEvent($pattern, function($matches){
		return null;
	});

	$toParse = "PING :irc.honkerbot.com";

	$bot->hook($toParse);

	$events = $events->getValue($bot);

	FUnit::equal(1, count($events[$pattern]));

});

FUnit::reset_fixtures();