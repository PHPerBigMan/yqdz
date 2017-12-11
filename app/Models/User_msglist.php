<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_msglist extends Model
{
    protected $table = 'user_msglist';
    public $timestamps = false;
    protected $fillable = ['uid','msgid','is_view'];//开启白名单字段
}
