<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dividedinto extends Model
{
    protected $table = 'dividedinto';
    protected $fillable = ['fromuid','level','money','status','created_at','orderid','uid'];
    public $timestamps = false;
    public function snop(){
        return $this->belongsTo('App\Models\Order_commodity_snop','orderid','orderid');
    }
}
