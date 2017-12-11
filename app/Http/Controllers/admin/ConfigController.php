<?php

namespace App\Http\Controllers\admin;

use App\Models\Weixin_config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ConfigController extends Controller
{
   public function edit(){
        $config=Weixin_config::all();

        return view('admin.config.edit',['config'=>$config]);
   }

   public function handle(Request $request){

       $logo="site_logo";
       $path=$request->file('site_logo');//上传不为空，则调用上传
       if (!empty($path)){
           foreach ($path as $k => $v){
               $pic[$k]='/'.$v->store('uploads','uploads');
           }

           $request->logo=json_encode($pic);
           Weixin_config::where('name',$logo)->update(['value'=>$request->logo]);
       }else{
           $res=json_decode(Weixin_config::where('name',$logo)->value('value'));
           $request->logo=json_encode($res);
           Weixin_config::where('name',$logo)->update(['value'=>$request->logo]);
       }
       $data=$request->except(['s','_token','site_logo']);
       $key = array_keys($data);
       unset($key[0]);
       sort($key);
       foreach ($key as $k => $v){
           $list=Weixin_config::where('name',"$v")->update(['value'=>$data[$v]]);
//           $list =DB::table('weixin_config')->where(['name'=>$v])->update(['value'=>$data[$v]]);

       }
       if ($list !==false){
               return response()->json(['status'=>200,'msg'=>"修改成功！"]);
           }else{
               Weixin_config::rollback();
               return response()->json(['status'=>404,'msg'=>"修改失败！"]);
           }
   }
}
