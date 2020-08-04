<?php

namespace Wanghanwanghan\Dbupdate\httpClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;

class fpm_client
{
    private $needJsonDecode=true;

    public function send($url='',$data=[],$headers=[],$options=[],$method='post')
    {
        $method=strtoupper($method);

        //新建请求
        $request=new Request($method,$url,$headers);

        //整理options
        $options['query']=$data;

        try
        {
            //发送请求
            $response=(new Client())->send($request,$options);

            //整理结果
            $data=$response->getBody()->getContents();

        }catch (GuzzleException $e)
        {
            return null;
        }

        return $this->needJsonDecode ? json_decode($data,true) : $data;
    }

    function needJsonDecode($type)
    {
        $this->needJsonDecode=$type;

        return $this;
    }








}
