<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Love extends Model
{
    protected $table = 'love';
    protected $fillable = ['uid','commodityid'];

    public $timestamps = false;
}
