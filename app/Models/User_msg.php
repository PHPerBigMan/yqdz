<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_msg extends Model
{
    protected $table = 'user_msg';
    public $timestamps = false;
    protected $fillable = ['type','result','uid','title','content','create_time'];//开启白名单字段
}
