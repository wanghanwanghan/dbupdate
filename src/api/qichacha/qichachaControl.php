<?php

namespace Wanghanwanghan\Dbupdate\api\qichacha;

class qichachaControl
{
    function dwdb()
    {
        $data=[
            'stockCode' => 300104,
            'pageIndex' => 1,
            'pageSize' => 10,
        ];

        $url='https://api.qichacha.com/IPO/GetIPOGuarantee';


        return (new qichachaBase())->get($url,$data);






    }






}
