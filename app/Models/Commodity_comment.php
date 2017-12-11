<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commodity_comment extends Model
{
    protected $table = 'commodity_comment';
    public $timestamps = false;
    protected $fillable = ['uid','content','created_at','commodityid','order_id'];//开启白名单字段
}
