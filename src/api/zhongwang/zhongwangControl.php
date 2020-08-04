<?php

namespace Wanghanwanghan\Dbupdate\api\zhongwang;

class zhongwangControl
{
    function jinxiang()
    {
        $url='invoice/getClientInvoices';

        $param['taxNumber'] = '91110108MA003D885L';
        $param['dataType'] = 2;
        $param['startDate'] = '1970-01-01';
        $param['endDate'] = '2020-08-04';
        $param['pageSize'] = 200;
        $param['currentPage'] = 1;

        $body['param'] = $param;

        $res=(new zhongwangBase())->post($url,$body);


        dd($res);



    }







}
