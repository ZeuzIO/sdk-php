<?php

class ZeuzAuth
{
    private $date = null;

    public function __construct($accessToken, $accessKey)
    {
        $this->accessToken = $accessToken;
        $this->accessKey = $accessKey;
    }
    
    public function generateSignature($requestBody, $url)
    {
        $parameters = [];
        $this->getRequestParameters($requestBody, $parameters);
        $stringToSign = $this->getStringToSign($parameters, $url);

        $tmp = $this->accessKey . "\n";;
        $tmp .= "sha512" . "\n";
        $tmp .= $this->getDate() . "\n";
        $tmp .= $stringToSign;

        return hash_hmac("sha512", $tmp, $this->accessToken);
    }

    private function getRequestParameters($requestBody, &$params)
    {
        if(is_object($requestBody) || is_array($requestBody))
        {
            foreach($requestBody AS $value)
            {
                if(is_object($value) || is_array($value))
                {
                    $this->getRequestParameters($value, $params);
                    continue;
                }

                if($value)
                {
                    $params[] = $value;
                }
            }
            return true;
        }
        return false;
    }

    private function getStringToSign($parameters, $url)
    {
        return implode("", $parameters) . $url;
    }

    public function getDate()
    {
        if(!$this->date)
        {
            $date = new \DateTime('now');
            $date->setTimezone(new \DateTimeZone("UTC"));
            return $date->format("Ymd\THis\Z");
        }
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getAccessKey()
    {
        return $this->accessKey;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }
};
