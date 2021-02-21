<?php

namespace knd27\Wablas;

class Wablas
{
    protected $token;
    protected $domain_api = "https://cepogo.wablas.com";

    public function __construct($token, $domain_api = null)
    {
        $this->token    = $token;
        if (isset($domain_api)) {
            $this->domain_api   = $domain_api;
        }
    }

    public function tes()
    {
        return $this->domain_api;
    }

    public function getInfo()
    {
        $url    = $this->domain_api . "/api/device/info?token=" . $this->token;
        return $this->_curlGet($url);
    }

    public function restartDevice()
    {
        $url    = $this->domain_api . "/api/device/reconnect?token=" . $this->token;
        return $this->_curlGet($url);
    }

    public function sendMessage($to, $msg)
    {
        $data = [
            'phone'     => $to,
            'message'   => $msg,
            'secret'    => true, // or true
            'priority'  => false, // or true
        ];

        $url    = $this->domain_api . "/api/send-message";
        return $this->_curlPost($url, $data);
    }

    public function sendImage($to, $imageurl, $caption = null)
    {
        $data = [
            'phone'     => $to,
            'caption'   => $caption,
            'image'     => $imageurl,
            'secret'    => false, // or true
            'priority'  => false, // or true
        ];
        $url    = $this->domain_api . "/api/send-image";
        return $this->_curlPost($url, $data);
    }







    private function _curlPost($url, $data)
    {
        $curl = curl_init();
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                "Authorization: " . $this->token,
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    private function _curlGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}
