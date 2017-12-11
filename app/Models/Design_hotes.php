<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Design_hotes extends Model
{
    protected $table = 'design_hotes';
    protected $fillable = ['uid','designid'];

    public $timestamps = false;
}
