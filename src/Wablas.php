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


    public function getInfo()
    {
        $url    = "/api/device/info?token=" . $this->token;
        $res = $this->httpClient->get($url);
        return $res->getBody();
    }

    public function getInfoWa()
    {
        $data   = json_decode($this->getInfo());
        $res = [
            'project_id'    => $data->data->project_id,
            'quota'         => $data->data->whatsapp->quota,
            'expired'       => $data->data->whatsapp->expired,
            'status'        => $data->data->whatsapp->status,
        ];
        return $res;
    }

    public function restartDevice()
    {
        $url    = "/api/device/reconnect?token=" . $this->token;
        $res = $this->httpClient->get($url);
        return $res->getBody();
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

    public function sendImage($imageCaption, $imageUrl)
    {
        if (!empty($this->recipients)) {
            $res = $this->httpClient->post('/api/send-image', [
                RequestOptions::HEADERS => $this->headers,
                RequestOptions::FORM_PARAMS => [
                    'phone'     => implode(", ", $this->recipients),
                    'caption'   => $imageCaption,
                    'image'     => $imageUrl,
                ],
            ]);
            return $res->getBody();
        }
        return false;
    }

    public function sendDocument($docUrl)
    {
        if (!empty($this->recipients)) {
            $res = $this->httpClient->post('/api/send-document', [
                RequestOptions::HEADERS => $this->headers,
                RequestOptions::FORM_PARAMS => [
                    'phone'     => implode(", ", $this->recipients),
                    'document'  => $docUrl,
                    'secret'    => false, // or true
                    'priority'  => false, // or true
                ],
            ]);
            return $res->getBody();
        }
        return false;
    }
}
