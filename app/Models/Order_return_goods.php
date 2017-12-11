<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_return_goods extends Model
{
    protected $table = 'order_return_goods';
    public $timestamps = false;
    protected $primaryKey='returnid';
    protected $fillable = ['orderid','uid','snopid','money','content','status','express','logistics','created_at'];
    public function order(){
        return $this->belongsTo('App\Models\Order','orderid','orderid');
    }
    public function user(){
        return $this->belongsTo('App\Models\User','uid','uid');
    }
    public function snop(){
        return $this->belongsTo('App\Models\Order_commodity_snop','snopid','snopid');
    }
}
