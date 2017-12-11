<?php

namespace App\Http\Controllers\admin;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    public function lists(){
        $data=Article::all();
        return view('admin.article.lists',['data'=>$data]);
    }

    public function edit(Request $request){
        $data=Article::where('articleid',$request->id)->first();
        $data['content']=html_entity_decode($data['content']);
        return view('admin.article.edit',['data'=>$data]);
    }
    public function add(Request $request){
        return view('admin.article.add');
    }

    public function handle(Request $request){
        if($request->articleid){
            $list=Article::where('articleid',$request->articleid)->update([
                'title'=>$request->title,
                'content'=>$request->content
            ]);
            if ($list !==false){
                return response()->json(['status'=>200,'msg'=>"修改成功"]);
            }else{
                return response()->json(['status'=>404,'msg'=>"修改失败"]);
            }
        }else{
            $list=Article::create([
                'title'=>$request->title,
                'content'=>$request->content
            ]);
            if ($list !==false){
                return response()->json(['status'=>200,'msg'=>"添加成功"]);
            }else{
                return response()->json(['status'=>404,'msg'=>"添加失败"]);
            }
        }


    }
}
