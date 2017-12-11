<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classify extends Model
{
    protected $table = 'classify';
    protected $fillable = ['name','type'];
    public $timestamps = false;

    public function getTypeAttribute($value){
        $title = "产品分类";
        if($value){
            // 定制信息
            $title = "定制分类";
        }

        return $title;
    }
}
