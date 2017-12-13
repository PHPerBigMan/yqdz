<?php

namespace App\Http\Controllers\admin;

use App\Models\Admin;
use App\Models\AdminGroup;
use App\Models\Statistics;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserPermession extends Controller
{
    public function error(){

        return view('error.Nopermession');
    }

}
