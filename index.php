<?php

use Wanghanwanghan\Dbupdate\laravelDB\laravelDB;
use Wanghanwanghan\Dbupdate\laravelDB\models\ts_oneSaid;

require __DIR__ . '/vendor/autoload.php';

$res=laravelDB::getInstance()->connection('ts')->table('oneSaid')->get()->toArray();



$res=ts_oneSaid::limit(5)->get()->toArray();


dd($res);













