<?php

namespace Wanghanwanghan\Dbupdate\laravelDB;

use Illuminate\Database\Capsule\Manager;
use Wanghanwanghan\Dbupdate\config\getGlobalConfig;

class laravelDB
{
    private static $instance;

    private function __construct(...$args)
    {
        $capsule = new Manager();

        $capsule->addConnection(getGlobalConfig::$DbInfo,'default');
        $capsule->addConnection(getGlobalConfig::$tssj,'ts');

        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

    static function getInstance(...$args)
    {
        if (!isset(self::$instance))
        {
            self::$instance = new static(...$args);
        }

        return self::$instance;
    }

    function connection($name='default')
    {
        return Manager::connection($name);
    }
}
