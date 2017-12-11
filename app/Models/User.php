<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';
    public $timestamps = false;
    protected $fillable = ['openid','nickname','money','img','isbuy','created_at'];//开启白名单字段
}
