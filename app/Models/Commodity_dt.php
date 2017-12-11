<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commodity_dt extends Model
{
    protected $table = 'commodity_dt';
    protected $fillable = ['goods_id','content','img','create_time','updated_at'];
    public $timestamps = false;
}
