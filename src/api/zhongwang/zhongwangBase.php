<?php

namespace Wanghanwanghan\Dbupdate\api\zhongwang;

use Wanghanwanghan\Dbupdate\config\getGlobalConfig;
use Wanghanwanghan\Dbupdate\httpClient\co_client;
use Wanghanwanghan\Dbupdate\httpClient\fpm_client;

class zhongwangBase
{
    private $url = 'http://api.zoomwant.com:50001/api/';
    private $url_test = 'http://211.157.177.35:50001/api/';
    private $aes;
    private $aes_test;
    private $taxNo;

    function __construct()
    {
        $this->taxNo = '91110108MA01KPGK0L';
        $key = 'ec598bad9fc64b38';
        $key_test = 'c90aacaee7276d41';
        $this->aes = new cryptAES($key);
        $this->aes_test = new cryptAES($key_test);
    }

    function post($url, $body, $type = 'test')
    {
        $header = [];

        $body['taxNo'] = $this->taxNo;

        $param = $body['param'];

        $json_param = json_encode($param);

        if (strtoupper($type) === 'TEST') {
            $encryptedData = $this->aes_test->encrypt($json_param);
            $url = $this->url_test . $url;
        } else {
            $encryptedData = $this->aes->encrypt($json_param);
            $url = $this->url . $url;
        }

        $base64_str = base64_encode($encryptedData);

        $body['param'] = $base64_str;

        if (getGlobalConfig::$fpmMode) {
            $data = (new fpm_client())->needJsonDecode(false)->send($url, $body, $header, [], 'post');
        } else {
            $data = (new co_client())->needJsonDecode(false)->send($url, $body, $header, [], 'post');
        }

        $data = base64_decode($data);

        if (strtoupper($type) === 'TEST') {
            $res = $this->aes_test->decrypt($data);
        } else {
            $res = $this->aes->decrypt($data);
        }

        return json_decode($res, true);
    }


}
