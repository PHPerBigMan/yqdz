<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminGroup extends Model
{
    protected $table = 'admin_group';
    protected $fillable = ['gname','miaoshu','ruleid'];
    public $timestamps = false;
}
