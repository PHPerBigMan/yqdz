<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carriage extends Model
{
    protected $table = 'carriage';
    protected $fillable = ['title','province','price'];
    public $timestamps = false;
}
