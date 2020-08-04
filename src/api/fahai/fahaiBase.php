<?php

namespace Wanghanwanghan\Dbupdate\api\fahai;

use Wanghanwanghan\Dbupdate\config\getGlobalConfig;
use Wanghanwanghan\Dbupdate\httpClient\co_client;
use Wanghanwanghan\Dbupdate\httpClient\fpm_client;

class fahaiBase
{
    private $authCode;
    private $rt;

    function __construct()
    {
        $this->authCode='25DwLghyWYbHM9P0OjwW';
        $this->rt=time()*1000;
    }

    function getList($url,$body)
    {
        $sign_num=md5($this->authCode.$this->rt);
        $doc_type=$body['doc_type'];
        $keyword=$body['keyword'];
        $pageno=$body['pageno'];
        $range=$body['range'];

        $json_data=[
            'dataType'=>$doc_type,
            'keyword'=>$keyword,
            'pageno'=>$pageno,
            'range'=>$range
        ];

        $json_data=json_encode($json_data);

        $data=[
            'authCode'=>$this->authCode,
            'rt'=>$this->rt,
            'sign'=>$sign_num,
            'args'=>$json_data
        ];

        if (getGlobalConfig::$fpmMode) return (new fpm_client())->send($url,$data);

        return (new co_client())->send($url,$data);
    }

    function getDetail($url,$body)
    {
        $sign_num=md5($this->authCode.$this->rt);

        $data=[
            'authCode'=>$this->authCode,
            'rt'=>$this->rt,
            'sign'=>$sign_num,
            'id'=>$body['id']
        ];

        if (getGlobalConfig::$fpmMode) return (new fpm_client())->send($url,$data);

        return (new co_client())->send($url,$data);
    }
}
