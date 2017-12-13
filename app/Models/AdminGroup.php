<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminGroup extends Model
{
    protected $table = 'admin_group';
    protected $fillable = ['gname','miaoshu','ruleid','groupid'];
    public $timestamps = false;
    public function getRuleidAttribute($value){
        return explode(',',$value);
    }
}
