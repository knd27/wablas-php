# wablas-php
Unofficial Wablas class : wablas.com

## Installation
Install dengan composer:

	composer require knd27/wablas-php:dev-main

## Example

### Login
ketika class ini di di buat, masukan token & domain_api.
secara default $domain_api = "https://cepogo.wablas.com";
	
	use knd27\Wablas\Wablas;
    $wablas = new Wablas('token');

### getInfo

    echo $wablas->getInfo()

### kirim pesan
    $wablas->sendMessage('0812345678', "pesan kamu");


menghasilkan respon json.
untuk line feed pada pesan bisa pake \n
