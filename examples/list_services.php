<?php
require_once("../src/ZeuzApi.php");

$tokenKey = 'YOUR_ACCESS_TOKEN';
$accessKey = 'YOUR_ACCESS_KEY';

$api = new ZeuzApi($tokenKey, $accessKey);

// {servergroupId} {gameProfileId}
$result = $api->listServices("1", "1");
if($result->success)
{
    foreach($result->services as $service)
    {
        $status = $api->getStatus($service->serviceId);
        $statistics = $api->getStatistics($service->serviceId)->statistics;
        echo "Service: [" . $service->serviceId ."] IP: " . $service->externalIp . ":" . $service->queryPort . " (". $status->status . ") '".$statistics->hostname."' | ". $statistics->playerCount . "/" . $statistics->maxPlayerCount ."\n";
    }
}
