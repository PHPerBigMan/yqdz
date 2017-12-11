<?php

namespace App\Http\Controllers\home;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CenterController extends Controller
{
    public function index(){
        $uid=$_COOKIE['uid'];
        $data=User::where('uid',$uid)->first();

        return view('home.center.index',['data'=>$data]);
    }
}
