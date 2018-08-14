<?php
require_once("../src/ZeuzApi.php");

$tokenKey = 'YOUR_ACCESS_TOKEN';
$accessKey = 'YOUR_ACCESS_KEY';

$api = new ZeuzApi($tokenKey, $accessKey);
// {serviceId}
$result = $api->reserveService("123");
if($result->success)
{
    echo "Service reserved: " .  $service->externalIp . ":" . $service->queryPort ."\n";
}
