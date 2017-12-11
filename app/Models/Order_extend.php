<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_extend extends Model
{
    protected $table = 'order_extend';
    protected $fillable = ['orderid','express','couriernumber','addjson','created_at','snopid'];
    public $timestamps = false;
}
