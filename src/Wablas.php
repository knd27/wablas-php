<?php

namespace knd27\Wablas;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;

class Wablas
{
    protected $token;
    protected $domain_api = "https://cepogo.wablas.com";
    protected $recipients = [];
    protected $httpClient;
    protected $headers;


    public function __construct($token, $domain_api = null)
    {
        $this->token    = $token;
        if (isset($domain_api)) {
            $this->domain_api   = $domain_api;
        }

        $this->headers = [
            'Accept' => 'application/json',
            'Authorization' => $this->token,
        ];

        $this->httpClient = new GuzzleClient([
            'base_uri' => $this->domain_api
        ]);
    }

    public function addRecipient($phoneNumber)
    {
        $this->recipients[] = $phoneNumber;
    }

    public function tes()
    {
        return $this->domain_api;
    }

    public function sendMessage($message, $type = 'random')
    {
        if (!empty($this->recipients)) {
            $res = $this->httpClient->post('/api/send-message', [
                RequestOptions::HEADERS => $this->headers,
                RequestOptions::FORM_PARAMS => [
                    'phone'     => implode(", ", $this->recipients),
                    'message'   => $message,
                    'type'      => $type
                ],
            ]);

            return $res->getBody();
        }
        return false;
    }

    public function getInfo()
    {
        $url    = "/api/device/info?token=" . $this->token;
        $res = $this->httpClient->get($url);
        return $res;
    }

    public function restartDevice()
    {
        $url    = $this->domain_api . "/api/device/reconnect?token=" . $this->token;
        return $this->_curlGet($url);
    }

    public function sendMessage1($to, $msg)
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
