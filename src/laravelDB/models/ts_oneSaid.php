<?php

namespace Wanghanwanghan\Dbupdate\laravelDB\models;

use Illuminate\Database\Eloquent\Model;

class ts_oneSaid extends Model
{
    protected $primaryKey='id';

    protected $connection='ts';

    protected $table='oneSaid';

    public $timestamps=true;

    protected $guarded=[];
}
