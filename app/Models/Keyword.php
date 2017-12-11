<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    protected $table = 'keyword';
    protected $fillable = ['uid','keyword'];

    public $timestamps = false;
}
