<?php

namespace Encrypted;

class Algorithms
{
    private $password = "XhcwO1NNI1Xi43EV";
    private $salt = "CK4OGOAtec0zgbNo";
    private $iv = "kE3nw8WVaORA8yAG";
    private $key_length;
    private $iterations;

    private $string_length;

    public function __construct() 
    {
        $this->key_length = 4;
        $this->iterations = 4;
        $this->string_length = 200;
	}

    private function openssl($password, $salt, $key_length, $iterations)
    {
        return openssl_pbkdf2($password, $salt, $key_length, $iterations, 'SHA256');
    }

    public function encrypt($string)
    {
        $string  = str_pad($string, $this->string_length);
        $openssl = $this->openssl($this->password, $this->salt, $this->key_length, $this->iterations);
        $encrypt = openssl_encrypt($string, 'AES-256-CBC', $openssl, OPENSSL_RAW_DATA, $this->iv);
        $encode  = base64_encode($encrypt);

        return rtrim(strtr($encode, '+/', '-_'), '=');
    }

    function decrypt($string)
    {
        $openssl = $this->openssl($this->password, $this->salt, $this->key_length, $this->iterations);
        $replace = strtr($string, '-_', '+/');
        $string  = str_pad($replace, strlen($string) % 4, '=', STR_PAD_RIGHT);
        $decode  = base64_decode($string);
        $decrypt = openssl_decrypt($decode, 'AES-256-CBC', $openssl, OPENSSL_RAW_DATA, $this->iv);
        
        return $decrypt;
    }
}
