<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'address';
    protected $fillable = ['is_default','name','phone','uid','province','city','district','address'];
    public $timestamps = false;
}
