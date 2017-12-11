<?php

namespace App\Http\Controllers\home;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    //佣金规则
    public function guize(){
        $data=Article::where('articleid',1)->first();

        return response()->json(['data'=>$data]);
    }
    //平台说明
    public function shuoming(){
        $data=Article::where('articleid',2)->first();

        return response()->json(['data'=>$data]);
    }
}
