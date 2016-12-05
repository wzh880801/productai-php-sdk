<?php

namespace ProductAI;

class Base
{
    const VERSION = '0.0.2';
    const API = 'https://api.productai.cn';

    protected $access_key_id;
    protected $secret_key;

    public $method;
    public $headers;
    public $body;

    public $headers2sign = [
        'x-ca-version',
        'x-ca-accesskeyid',
        'x-ca-timestamp',
        'x-ca-signaturenonce',
        'requestmethod',
    ];

    public $curl_opt;
    public $curl_info;
    public $curl_errno;
    public $curl_error;

    public $tmpfile;

    public function __construct($access_key_id, $secret_key)
    {
        $this->access_key_id = $access_key_id;
        $this->secret_key = $secret_key;

        $this->initialize();
    }

    public function initialize()
    {
        $this->method = 'POST';

        $this->headers = [
            'x-ca-version' => 1,
            'x-ca-accesskeyid' => $this->access_key_id,
            'x-ca-timestamp' => time(),
            'x-ca-signaturenonce' => $this->generateNonce(16),
            'user-agent' => "ProductAI-SDK-PHP/{$this->version()} (+http://www.productai.cn)",
        ];

        $this->body = [];

        $this->curl_opt = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
        ];
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
        $headers = [];
        foreach ($this->headers as $k => $v) {
            if (in_array($k, $this->headers2sign)) {
                $headers[$k] = $v;
            }
        }

        $body = [];
        foreach ($this->body as $k => $v) {
            if (is_string($v)) {
                $body[$k] = $v;
            }
        }

        $requests = array_merge($headers, $body);
        ksort($requests);

        return base64_encode(hash_hmac('sha1', urldecode(http_build_query($requests)), $this->secret_key, true));
    }

    public function curl($service_type, $service_id)
    {
        $curl = curl_init("{$this->api()}/$service_type/$service_id");

        $this->curl_opt[CURLOPT_CUSTOMREQUEST] = $this->method;

        $this->headers['requestmethod'] = $this->method;
        $this->headers['x-ca-signature'] = $this->signRequests();

        foreach ($this->headers as $k => $v) {
            $headers[] = "$k: $v";
        }

        $this->curl_opt[CURLOPT_HTTPHEADER] = $headers;

        if ($this->body) {
            $this->curl_opt[CURLOPT_POSTFIELDS] = $this->body;
        }

        curl_setopt_array($curl, $this->curl_opt);

        $output = curl_exec($curl);

        $this->curl_info = curl_getinfo($curl);
        $this->curl_errno = curl_errno($curl);
        $this->curl_error = curl_error($curl);

        curl_close($curl);

        return json_decode($output, true);
    }

    public function convertArrayToCSV($array)
    {
        $replace = function ($str) {
            return '"'.str_replace('"', '\"', $str).'"';
        };

        foreach ($array as &$v) {
            if (is_array($v)) {
                foreach ($v as &$val) {
                    $val = $replace($val);
                }

                $v = implode(',', $v);
            } else {
                $v = $replace($v);
            }
        }

        $this->tmpfile = tmpfile();
        fwrite($this->tmpfile, implode("\n", $array));

        return stream_get_meta_data($this->tmpfile)['uri'];
    }
}
