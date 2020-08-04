<?php

namespace Wanghanwanghan\Dbupdate\api\taoshu;

class taoshuControl
{
    function qygsjj()
    {
        $body=[
            'entName' => '北京每日信动科技有限公司',
        ];

        $service='getRegisterInfo';

        $res=(new taoshuBase())->post($body,$service);




        dd($res);








    }







}
