<?php

namespace Wanghanwanghan\Dbupdate\httpClient;

use EasySwoole\HttpClient\HttpClient;

class co_client
{
    private $needJsonDecode=true;

    public function send($url='',$data=[],$headers=[],$options=[],$method='post')
    {
        $method=strtoupper($method);

        //新建请求
        $request=new HttpClient($url);

        //设置head头
        empty($headers) ?: $request->setHeaders($headers,true,false);

        try
        {
            //发送请求
            $method === 'POST' ? $data=$request->post($data) : $data=$request->get();

            //整理结果
            $data=$data->getBody();

        }catch (\Exception $e)
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
