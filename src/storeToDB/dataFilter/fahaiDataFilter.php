<?php

namespace Wanghanwanghan\Dbupdate\storeToDB\dataToDB;

class fahaiDataFilter
{
    private $dataType;
    private $type;//list or detail

    function __construct($dataType,$type)
    {
        $this->dataType = $dataType;

        $this->type = $type;
    }

    function filter($data)
    {
        return true;
    }


}
