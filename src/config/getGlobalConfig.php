<?php

namespace Wanghanwanghan\Dbupdate\config;

class getGlobalConfig
{
    static $fpmMode = true;

    static $DbInfo = [
        'driver' => 'mysql',
        'host' => 'rm-2ze576080p40m5htro.mysql.rds.aliyuncs.com',
        'port' => '3306',
        'database' => 'mrxd',
        'username' => 'mrxd',
        'password' => 'GUTvUu$9438W874e',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'strict' => false,
        'prefix' => '',
    ];

    static $tssj = [
        'driver' => 'mysql',
        'host' => '183.136.232.236',
        'port' => '3306',
        'database' => 'grid_game',
        'username' => 'chinaiiss',
        'password' => 'chinaiiss',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'strict' => false,
        'prefix' => '',
    ];





    //法海dataType对应的库表关系
    static $fahaiDB = [
        '涉诉' => [
            'cpws' => '',
        ],
    ];



}
