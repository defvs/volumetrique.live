<?php
	const CALENDAR_URL = "https://ics.teamup.com/feed/kszbuw32aqbuq6fadi/9131558.ics";
	const CACHE_TTL = 900;
	
	if ($_SERVER['REQUEST_METHOD'] !== "GET") {
		http_response_code(404);
		echo("Not Found");
		exit();
	}
	
	$cached_time = (int)@file_get_contents("./cache");
	
	if ($cached_time === false || ($cached_time + CACHE_TTL) < time()) {
		$file = @file_get_contents(CALENDAR_URL);
		@file_put_contents("./calendar.ics", $file);
		@file_put_contents("./cache", time());
	} else {
		$file = @file_get_contents("./calendar.ics");
	}
	
	if ($file === false) {
		http_response_code(500);
		echo("An error occured.");
		exit();
	}
	
	http_response_code(200);
	echo($file);
	exit();