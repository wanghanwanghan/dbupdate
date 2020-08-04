<?php

namespace Wanghanwanghan\Dbupdate\api\fahai;

use Wanghanwanghan\Dbupdate\storeToDB\dataToDB\fahaiDataFilter;

class fahaiControl
{
    //涉诉列表地址
    private $ss_list_url='https://api.fahaicc.com/v2/query/sifa';

    private function checkToFilter($dataType,$type,$res)
    {
        if (isset($res['code']) && $res['code']==='s')
        {
            if ($type === 'list')
            {
                if (isset($res["{$dataType}List"]) && !empty($res["{$dataType}List"]))
                {
                    //这里可以判断是否需要入库或者别的逻辑
                    return (new fahaiDataFilter($dataType,'list'))->filter($res);
                }

            }else if ($type === 'detail')
            {




            }else
            {
                return true;
            }
        }

        return true;
    }

    //涉诉
    function ss_list($entName,$dataType,$page=1,$pageSize=10)
    {
        $params=[
            'doc_type'=>$dataType,
            'keyword'=>$entName,
            'pageno'=>$page,
            'range'=>$pageSize
        ];

        $res=(new fahaiBase())->getList($this->ss_list_url,$params);

        return $this->checkToFilter($dataType,'list',$res);
    }








}
