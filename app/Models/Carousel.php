<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    protected $table = 'carousel';
    protected $fillable = ['position','title','carouselimg','url'];
    public $timestamps = false;
}
