<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    protected $table = 'design';
    public $timestamps = false;
    protected $fillable = ['title','content','img','phone','uid','created_at','is_qiye','cate_id'];
    protected $primaryKey='designid';
    public function user(){
        return $this->belongsTo('App\Models\User','uid','uid');
    }

    public function getImgAttribute($value){
        if(!empty($value)){
            return json_decode($value,true);
        }
//        return 1;
    }
}
