<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carriage_extend extends Model
{
    protected $table = 'carriage_extend';
    protected $fillable = ['carriageid','takeprovince','price','first_price','extra_price'];
    public $timestamps = false;
}
