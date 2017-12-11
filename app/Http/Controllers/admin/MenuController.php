<?php

namespace App\Http\Controllers\admin;

use App\Models\Menu;
use App\Models\Weixin_config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
   public function edit(){
       $map['pid'] = 0;
       $menu=Menu::where('pid',$map['pid'])->get()->toArray();//获取一级分类

       if(!empty($menu)){
           foreach ($menu as $k => $v){
               $temp[] = $v;
               $map['pid'] = $v['menuid'];
               $seconde = Menu::where('pid',$map['pid'])->get()->toArray();//获取二级菜单
               foreach ($seconde as $k1 => $v1){
                   $temp[] = $v1;
               }
           }
       }else{
           $temp = [];
       }
       return view('admin.menu.edit',['data'=>json_encode($temp)]);
   }

   public function save(Request $request){
       $post=$request->data;

       if($post['menuid'] != 0){
           $res=Menu::where('menuid',$post['menuid'])->update([
               'pid'=>$post['pid'],
               'level'=>$post['level'],
               'name'=>$post['name'],
               'sort'=>$post['sort'],
               'value'=>$post['value']
           ]);
           if($res !== false){
               return response()->json(['status' => '200', $post['menuid']]);
           }else{
               return response()->json(['status' => '404']);
           }
       }else{
           $res=Menu::create([
               'pid'=>$post['pid'],
               'level'=>$post['level'],
               'name'=>$post['name'],
               'sort'=>$post['sort'],
               'value'=>$post['value']
           ]);
           if ($res){
               return response()->json(['status' => '200', $res]);
           }else{
               return response()->json(['status' => '404']);
           }
       }
   }

   public function del(Request $request){
       $count=Menu::count();
       if($count > 1){
           if(Menu::where('pid',$request->id)->first()){
               return response()->json(['status'=>404,'msg'=>"请先删除下级菜单！"]);
           }

           if(Menu::where('menuid',$request->id)->delete()){
               return response()->json(['status' => '200', 'msg' =>'删除成功!',]);
           }else{
               return response()->json(['status' => '404', 'msg' =>'删除失败!',]);
           }
       }else{
           return response()->json(['status'=>404,'msg'=>"必须保留一个！"]);
       }
   }

   public function update(){
        $config=Weixin_config::all()->toArray();
        foreach ($config as $k => $v){
            if($v['name'] == "appid"){
                $options['app_id'] = $v['value'];
            }elseif($v['name'] == "secret"){
                $options['secret'] = $v['value'];
            }elseif($v['name'] == "token"){
                $options['token'] = $v['value'];
            }
        }

   }
}
