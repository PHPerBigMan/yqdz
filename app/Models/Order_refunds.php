<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_refunds extends Model
{
    protected $table = 'order_refunds';
    public $timestamps = false;
    protected $primaryKey='refundsid';
    protected $fillable = ['status','created_at','orderid'];//开启白名单字段
    public function order(){
        return $this->belongsTo('App\Models\Order','orderid','orderid');
    }
}
