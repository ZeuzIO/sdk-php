<?php

require_once("ZeuzAuth.php");

class ZeuzApi
{
    const API_URL = 'https://api.zeuz.io/v2';
    
    private $auth = null;
    
    public function __construct($accessToken, $accessKey)
    {
        $this->auth = new ZeuzAuth($accessToken, $accessKey);
    }

    private function APICall($method, $endpoint, $requestBody = [])
    {
        $url = self::API_URL . $endpoint;
        $signature = $this->auth->generateSignature($requestBody, $url);
        $jsonBody = json_encode($requestBody);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        $headers = [
            "X-Zeuz-Date: ". $this->auth->getDate(),
            "Content-Type: application/json",
            "Authorization: ZEUZ-HMAC-SHA512 {$this->auth->getAccessKey()}:{$signature}"
        ];

        if($method != "get")
        {
            $headers[] = 'Content-Length: '.strlen($jsonBody);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonBody);
        }else{
            $headers[] = 'Content-Length: 0';
        }
        
        if($method == "post")
        {
            curl_setopt($curl, CURLOPT_POST, 01);
        }
        if($method == "put")
        {
            curl_setopt($curl, CURLOPT_PUT, 0);
        }
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLINFO_HEADER_OUT, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        if($result)
        {
            return json_decode($result);
        }
        return null;
    }

    public function listServices($serverGroupId, $gameProfileId)
    {
        return $this->APICall("get", "/service/listServices/" . $gameProfileId . "/" . $serverGroupId);
    }

    public function requestService($serverGroupId, $gameProfileId)
    {
        return $this->APICall("get", "/service/provide/" . $gameProfileId . "/" . $serverGroupId);
    }

    public function reserveService($serviceId)
    {
        $request = [
            "serviceId" => $serviceId
        ];
        return $this->APICall("post", "/service/reserve", $request);
    }

    public function unreserveService($serviceId)
    {
        $request = [
            "serviceId" => $serviceId
        ];
        return $this->APICall("post", "/service/unreserve", $request);
    }

    public function getStatus($serviceId)
    {
        return $this->APICall("get", "/service/status/" . $serviceId);
    }

    public function getService($serviceId)
    {
        return $this->APICall("get", "/service/" . $serviceId);
    }
    
    public function allocateService($serverGroupId, $gameProfileId)
    {
        $request = [
            "gameProfileId" => $gameProfileId,
            "serverGroupId" => $serverGroupId,
        ];
        return $this->APICall("post", "/service/allocate", $request);
    }

    public function unallocateService($serviceId)
    {
        return $this->APICall("get", "/service/unallocate/" . $serviceId);
    }

    public function start($serviceId)
    {
        return $this->APICall("get", "/service/start/" . $serviceId);
    }

    public function stop($serviceId)
    {
        return $this->APICall("get", "/service/stop/" . $serviceId);
    }

    public function restart($serviceId)
    {
        return $this->APICall("get", "/service/restart/" . $serviceId);
    }

    public function reinstall($serviceId)
    {
        return $this->APICall("get", "/service/reinstall/" . $serviceId);
    }

    public function postStatistics($serviceId, $playercount, $maxPlayerCount)
    {
        $request = [
            "playerCount" => $playercount,
            "maxPlayerCount" => $maxPlayerCount,
        ];
        return $this->APICall("post", "/statistics/ccu/" . $serviceId, $request);
    }

    public function getStatistics($serviceId)
    {
        return $this->APICall("get", "/statistics/ccu/" . $serviceId);
    }
};