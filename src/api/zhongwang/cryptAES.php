<?php

namespace Wanghanwanghan\Dbupdate\api\zhongwang;

class cryptAES
{
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    function encrypt($str)
    {
        return openssl_encrypt($str, 'aes-128-ecb', $this->key, OPENSSL_RAW_DATA);
    }

    function decrypt($encryptedStr)
    {
        return openssl_decrypt($encryptedStr, 'aes-128-ecb', $this->key, OPENSSL_RAW_DATA);
    }
}