<?php

namespace App\Http\Controllers\admin;

use App\Models\Commodity;
use App\Models\Commodity_comment;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommodityCommentController extends Controller
{
    public function lists(Request $request){

        if($request->type ==1){
            $data=Commodity_comment::where('commodityid',$request->keyword)->orderBy('commodity_commentid', 'desc')->paginate(5);
        }else{
            $data=Commodity_comment::orderBy('created_at', 'desc')->paginate(5);
//            dd($data);
        }
        foreach ($data as $k => $v){
//            echo $v->commodityid;
            $data[$k]['title']=Commodity::where('commodityid',$v->commodityid)->select('title')->orderBy('commodityid', 'desc')->first();

            $data[$k]['nickname']=User::where('uid',$v->uid)->select('nickname')->first();
//            $data[$k]['img']=Commodity::where('uid',$v['img '])->select('img')->first();
        }
//        dd($data);
        return view('admin.commoditycomment.lists',['data'=>$data]);
    }

    public function del(Request $request){
        if(Commodity_comment::where('commodity_commentid',$request->id)->delete()){
            return response()->json(['status' => '200', 'msg' =>'删除成功!',]);
        }else{
            return response()->json(['status' => '404', 'msg' =>'删除失败!',]);
        }
    }

    //public function huifu(Request $request){
    //    $post = I('post.');
    //    $map['commodity_commentid'] = $post['id'];
    //
    //    if(Commodity_comment::where('commodity_commentid',$request->id)->update(['seller_content'=>$request->content])){
    //        return response()->json(['status' => '200', 'msg' =>'回复成功!',]);
    //    }else{
    //        return response()->json(['status' => '404', 'msg' =>'回复失败!',]);
    //    }
    //}
}
