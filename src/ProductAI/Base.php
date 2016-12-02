<?php

namespace ProductAI;

class Base
{
    const VERSION = '0.0.1';
    const API = 'https://api.productai.cn';

    protected $access_key_id;
    protected $secret_key;

    public $method;
    public $headers;
    public $body;

    public $curl_timeout = 30;
    public $curl_info;
    public $curl_errno;
    public $curl_error;

    public function __construct($access_key_id, $secret_key)
    {
        $this->access_key_id = $access_key_id;
        $this->secret_key = $secret_key;

        $this->initialize();
    }

    public function initialize()
    {
        $this->method = 'POST';
        $this->headers = [];
        $this->body = [];
    }

    public static function version()
    {
        return static::VERSION;
    }

    public static function api()
    {
        return static::API;
    }

    public function generateNonce($len, $chars = '')
    {
        if (!$chars) {
            $chars = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));
        } elseif (!is_array($chars)) {
            $chars = str_split($chars);
        }

        $index = count($chars) - 1;

        $nonce = '';
        for ($i = 0; $i < $len; ++$i) {
            $nonce .= $chars[mt_rand(0, $index)];
        }

        return $nonce;
    }

    public function signRequests()
    {
        $requests = array_merge($this->headers, $this->body);
        ksort($requests);

        return hash_hmac('sha1', urldecode(http_build_query($requests)), $this->secret_key);
    }

    public function curl($service_type, $service_id)
    {
        $ch = curl_init("{$this->api()}/$service_type/$service_id");

        $this->headers = [
            'x-ca-version' => 1,
            'x-ca-accesskeyid' => $this->access_key_id,
            'x-ca-timestamp' => time(),
            'x-ca-signaturenonce' => $this->generateNonce(16),
            'user-agent' => "ProductAI-SDK-PHP/{$this->version()} (+http://www.productai.cn)",
            'requestmethod' => $this->method,
        ];

        $this->headers['x-ca-signature'] = $this->signRequests();

        foreach ($this->headers as $k => $v) {
            $headers[] = "$k: $v";
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $this->body,
            CURLOPT_TIMEOUT => $this->curl_timeout,
        ]);

        $output = curl_exec($ch);

        $this->curl_info = curl_getinfo($ch);
        $this->curl_errno = curl_errno($ch);
        $this->curl_error = curl_error($ch);

        curl_close($ch);

        return json_decode($output, true);
    }

    public function convertArrayToCSV($array)
    {
        foreach ($array as &$v) {
            foreach ($v as &$val) {
                $val = '"'.str_replace('"', '\"', $val).'"';
            }

            $v = implode(',', $v);
        }

        return implode("\n", $array);
    }
}
