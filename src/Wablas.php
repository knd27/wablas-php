<?php

namespace knd27\Wablas;

class Wablas
{
    protected $token;

    public function __construct($token)
    {
        $this->token    = $token;
    }

    public function tes()
    {
        return $this->token;
    }
}
