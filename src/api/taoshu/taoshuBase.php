<?php

namespace Wanghanwanghan\Dbupdate\api\taoshu;

use Wanghanwanghan\Dbupdate\config\getGlobalConfig;
use Wanghanwanghan\Dbupdate\httpClient\co_client;
use Wanghanwanghan\Dbupdate\httpClient\fpm_client;

class taoshuBase
{
    private $taoshuPEM = <<<Eof
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtcMMmeWzcLuIo6hlv5nb
ZJmavpdpZxoIWm9+yJ6Sr10LzNzms9vK0USuO6GV+5qg4IcGyPn8e0mUkE6pNs+O
kvqd559pyZ1BUeU3SGAMPNAtnYUZ8/CAmJw+xrNicpGT1z7K134vir/VqZxGFdlZ
4JZA7JvV/+apICnueSqKGUDud366UjddstNzuPjzDSBkwwDGez9bps+YIJdEwLO+
GotaLcLTUyWXLvQ0VWHjVMd1br7K6LVvZrF348a5B/25FEVEwdJ0SIHk/F0PSMAc
zOw9ccqcNMJ5CKiwEnITpzq+bpsK1kacfBEw2xu7MjFctSJ7X6KU/2qp3CjfTNhk
OQIDAQAB
-----END PUBLIC KEY-----
Eof;

    private $url = 'http://api.longdundata.com/service';

    private $uid = '24d2511a7e3b4f818ea268fed30ef0f2';

    function __construct()
    {
        openssl_get_publickey($this->taoshuPEM);
    }

    private function authCode($string, $operation = 'DECODE', $key = '', $expiry = 0)
    {
        $ckey_length = 4;

        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);

        $result = '';
        mt_srand();
        $box = range(0, 255);

        $rndkey = [];
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if ($operation == 'DECODE') {
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc . str_replace('=', '', base64_encode($result));
        }
    }

    private function quantumEncode($source, $key)
    {
        $result = [];
        $random = md5(time());
        $source = urlencode($source);
        //des 加密
        $value = $this->authCode($source, 'ENCODE', $random);
        $result['value'] = $value;
        //rsa加密
        $encrypt = '';
        openssl_public_encrypt($random, $encrypt, $key);
        $encrypt = base64_encode($encrypt);
        $result['key'] = $encrypt;
        return $result;
    }

    private function quantumDecode($result, $key)
    {
        $_key = $result->key;
        $_value = $result->value;
        $_key = base64_decode($_key);
        //rsa解密
        $decrypt = '';
        openssl_public_decrypt($_key, $decrypt, $key);
        //des解密
        $_value = mb_convert_encoding($_value, 'UTF-8', 'ASCII');
        $_value = $this->authCode($_value, 'DECODE', $decrypt);
        return urldecode($_value);
    }

    function post($body, $service)
    {
        $header = [];

        $postBody = [
            'uid' => $this->uid,
            'service' => $service,
            'params' => $body
        ];

        $postBodyJson = $this->quantumEncode(json_encode($postBody), $this->taoshuPEM);

        //参数固定格式
        $p_arr['uid'] = $this->uid;
        $p_arr['data'] = json_encode($postBodyJson);

        if (getGlobalConfig::$fpmMode) {
            $data = (new fpm_client())->needJsonDecode(false)->send($this->url, $p_arr, $header, [], 'post');
        } else {
            $data = (new co_client())->needJsonDecode(false)->send($this->url, $p_arr, $header, [], 'post');
        }

        $data = urldecode($data);

        $rs = $this->quantumDecode(json_decode($data), $this->taoshuPEM);

        return json_decode($rs, true);
    }


}
