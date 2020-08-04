<?php

namespace Wanghanwanghan\Dbupdate\api\qianqi;

use Wanghanwanghan\Dbupdate\config\getGlobalConfig;
use Wanghanwanghan\Dbupdate\httpClient\co_client;
use Wanghanwanghan\Dbupdate\httpClient\fpm_client;

class qianqiBase
{
    private $usercode;
    private $userkey;
    private $fpmMode;

    function __construct()
    {
        $this->usercode = 'j7sCrudkgE';
        $this->userkey = 'EBpnYXaxKDi';
        $this->fpmMode = getGlobalConfig::$fpmMode;
    }

    private function createToken($params, $str = '')
    {
        ksort($params);

        foreach ($params as $k => $val) {
            $str .= $k . $val;
        }

        $res = hash_hmac('sha1', $str . $this->usercode, $this->userkey);

        return $res;
    }

    private function getEntid($entName)
    {
        $ctype = preg_match('/\d{5}/', $entName) ? 1 : 3;

        $data = [
            'key' => $entName,
            'ctype' => $ctype,
            'usercode' => $this->usercode
        ];

        $token = $this->createToken($data);

        $header = [
            'content-type' => 'application/x-www-form-urlencoded',
            'authorization' => $token
        ];

        if ($this->fpmMode) {
            $res = (new fpm_client())->send('http://39.106.92.123/getentid/', $data, $header);
        } else {
            $res = (new co_client())->send('http://39.106.92.123/getentid/', $data, $header);
        }

        if (!empty($res) && !empty($res['data'])) {
            return $res['data'];
        } else {
            return null;
        }
    }

    //一年的财务数据
    public function oneYearData($entName, $year)
    {
        $entid = $this->getEntid($entName);

        $data = [
            'entid' => $entid,
            'year' => $year,
            'usercode' => $this->usercode
        ];

        $token = $this->createToken($data);

        $header = [
            'content-type' => 'application/x-www-form-urlencoded',
            'authorization' => $token
        ];

        if ($this->fpmMode) {
            $res = (new fpm_client())->send('http://39.106.92.123/xindong/search/', $data, $header);
        } else {
            $res = (new co_client())->send('http://39.106.92.123/xindong/search/', $data, $header);
        }

        return $res;
    }


}
