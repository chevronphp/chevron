<?php

// FUnit::setup(function(){
	$bot = new \Chevron\HonkerBot\Commands;
	FUnit::fixture("BOT", $bot);
// });

// FUnit::test(sprintf("%s: %s", $label, ++$count), function(){};

FUnit::test("Commands::pong() return format", function(){
	$bot = FUnit::fixture("BOT");

	$code = "a67s67d89f09";
	$response = $bot->pong($code);
	$expected = "PONG :{$code}\n";

	FUnit::equal($expected, $response);
});

FUnit::test("Commands::user() return format", function(){
	$bot = FUnit::fixture("BOT");

	$nick = "HonkerBot";
	$response = $bot->user($nick);
	$expected = "USER {$nick} 0 * :{$nick}\n";

	FUnit::equal($expected, $response);
});

FUnit::test("Commands::nick() return format", function(){
	$bot = FUnit::fixture("BOT");

	$nick = "HonkerBot";
	$response = $bot->nick($nick);
	$expected = "NICK {$nick}\n";

	FUnit::equal($expected, $response);
});

FUnit::test("Commands::pass() return format", function(){
	$bot = FUnit::fixture("BOT");

	$pass = "a67s67d89f09";
	$response = $bot->pass($pass);
	$expected = "PASS {$pass}\n";

	FUnit::equal($expected, $response);
});

FUnit::test("Commands::join() return format", function(){
	$bot = FUnit::fixture("BOT");

	$chan = "#HonkerBot";
	$response = $bot->join($chan);
	$expected = "JOIN {$chan}\n";

	FUnit::equal($expected, $response);
});

FUnit::test("Commands::notice() return format", function(){
	$bot = FUnit::fixture("BOT");

	$user = "HonkerBot";
	$msg  = "Test Message";
	$response = $bot->notice($user, $msg);
	$expected = "NOTICE {$user} :{$msg}\n";

	FUnit::equal($expected, $response);
});

FUnit::test("Commands::msg() return format", function(){
	$bot = FUnit::fixture("BOT");

	$user = "HonkerBot";
	$msg  = "Test Message";
	$response = $bot->msg($user, $msg);
	$expected = "PRIVMSG {$user} :{$msg}\n";

	FUnit::equal($expected, $response);
});

FUnit::test("Commands::me() return format", function(){
	$bot = FUnit::fixture("BOT");

	$user = "HonkerBot";
	$msg  = "Test Message";
	$response = $bot->me($user, $msg);
	$expected = "PRIVMSG {$user} :\x01ACTION {$msg}\x01\n";

	FUnit::equal($expected, $response);
});

FUnit::reset_fixtures();