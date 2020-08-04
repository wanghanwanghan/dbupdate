<?php

namespace Wanghanwanghan\Dbupdate\api\qichacha;

use Wanghanwanghan\Dbupdate\config\getGlobalConfig;
use Wanghanwanghan\Dbupdate\httpClient\co_client;
use Wanghanwanghan\Dbupdate\httpClient\fpm_client;

class qichachaBase
{
    private $appkey;
    private $seckey;

    function __construct()
    {
        $this->appkey = "8ede3b327d964a989a1152ac1d4ea0ae";
        $this->seckey = "745EC919B838D8929ED1F653B768A755";
    }

    function get($url, $body)
    {
        $time = time();

        $token = strtoupper(md5($this->appkey . $time . $this->seckey));

        $header = ['Token' => $token, 'Timespan' => $time];

        $body['key'] = $this->appkey;

        $url .= '?' . http_build_query($body);

        if (getGlobalConfig::$fpmMode) return (new fpm_client())->send($url, $body, $header, [], 'get');

        return (new co_client())->send($url, $body, $header, [], 'get');
    }


}
