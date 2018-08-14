<?php
require_once("../src/ZeuzApi.php");

$tokenKey = 'YOUR_ACCESS_TOKEN';
$accessKey = 'YOUR_ACCESS_KEY';

$api = new ZeuzApi($tokenKey, $accessKey);

// Lists all game servers in a servergroup / game profile
$result = $api->listServices("1", "1");

// Reserves a service
$result = $api->reserveService("1", "1");

// Unreserves a service
$result = $api->unreserveService("123");

// Request a new auto scaled game server (Finds the first service available that is not reserved, reserves it and returns the service)
$result = $api->requestService("1", "1");

// Get status about a game server
$result = $api->getStatus("123");

// Allocate a new game server (Not-Scaled)
$result = $api->allocateService("1", "1");

// Destroy a previously allocated game server (Not-Scaled)
$result = $api->unallocateService("123");

// Get information about a service (IP, Port etc)
$result = $api->getService("123");

// Stop a game server
$result = $api->stop("123");

// Start a game server
$result = $api->start("123");


// Restart the game server
$result = $api->restart("123");

// Reinstall a game server
$result = $api->reinstall("123");


// Retrieve CCU information about the game server
$result = $api->getStatistics("123");

// Post CCU information about the game server
$result = $api->postStatistics("444", 16, 32);

// print to see return data or have a look at our api documentation (readme.md)
var_dump($result);