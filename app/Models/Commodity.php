<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commodity extends Model
{
    protected $table = 'commodity';
    protected $fillable = ['nickname','describes','experts','title','thumbnail','fx_thumb','money','number','sales','hostess','content','carrousel','starttime','endtime','firstgraded','secondgraded','threegraded','status','recommended','past','appraisal','classifyid','labelid','carriageid','img','is_hot','recom_order','hot_order','stock'];
    public $timestamps = false;


}
