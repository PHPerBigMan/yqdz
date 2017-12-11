<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order';
    public $timestamps = false;
    protected $primaryKey='orderid';
    protected $fillable = ['uniqueid','addressid','address_json','money','carriage','beizhu','label','transaction',
        'commercial','evaluation_state','order_state','refund_state','return_status','refund_amount','is_fencheng','pay_time',
        'created_at','endtime','uid'];

    public function user(){
        return $this->belongsTo('App\Models\User','uid','uid');
    }
    public function address(){
        return $this->belongsTo('App\Models\Address','addressid','addressid');
    }
    public function snop(){
        return $this->hasMany('App\Models\Order_commodity_snop','orderid','orderid');
    }
    public function extend(){
        return $this->belongsTo('App\Models\Order_extend','orderid','orderid');
    }

}

