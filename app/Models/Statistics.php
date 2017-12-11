<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistics extends Model
{
    protected $table = 'statistics';
    protected $fillable = ['order_nub','order_money','browse_nub','visitor_nub','created_at'];
    public $timestamps = false;
}
