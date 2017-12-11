<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Express extends Model
{
    protected $table = 'express';
    protected $fillable = ['title'];
    public $timestamps = false;
}
