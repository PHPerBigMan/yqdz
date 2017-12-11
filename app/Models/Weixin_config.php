<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Weixin_config extends Model
{
    protected $table = 'weixin_config';
    protected $fillable = ['name','value'];
    public $timestamps = false;
}
