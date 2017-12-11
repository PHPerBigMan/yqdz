<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';
    protected $fillable = ['cartid','uid','commodityid','nums','money'];
    public $timestamps = false;
}
 