<?php

namespace App\Http\Controllers\admin;

use App\Models\Carousel;
use App\Models\Carousel_extend;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarouselController extends Controller
{
    public function lists(){
        $data=Carousel::paginate(10);
        foreach ($data as $k =>$v){
            $res=Carousel_extend::where('extendid',$v['position'])->select('name')->first();
            $data[$k]['name']=$res['name'];
        }
        return view('admin.carousel.lists',['data' =>$data]);
    }

    public function add()
    {
        $extend=Carousel_extend::all();

        return view('admin.carousel.add',['extend' =>$extend]);
    }

    public function handle(Request $request)
    {

        if(empty($request->carouselid)){
            $path=$request->file('suolvetu');
            if (!empty($path)){
                foreach ($path as $k => $v){
                    $pic[$k]='/'.$v->store('uploads','uploads');
                }
                $request->suolvetu=json_encode($pic);
            }else{
                return response()->json(['status'=>404,'msg'=>"请选择图片"]);
            }
            $res=Carousel::create([
                'position'=>$request->position,
                'title'=>$request->title,
                'carouselimg'=>$request->suolvetu,
                'url'=>$request->url,
            ]);
            if ($res){
                return response()->json(['status'=>200,'msg'=>"添加成功"]);
            }else{
                return response()->json(['status'=>404,'msg'=>"添加失败"]);
            }
        }else{
            $path=$request->file('suolvetu');
            if (!empty($path)){
                foreach ($path as $k => $v){
                    $pic[$k]='/'.$v->store('uploads','uploads');
                }
//                $db_pic=Carousel::where('carouselid',$request->carouselid)->value('carouselimg');
//                if(!empty($db_pic)){
//                    $db_pic=json_decode($db_pic,true);
//                    $pic=array_merge($db_pic,$pic);
//                }
                $request->suolvetu=json_encode($pic);
            }else{
                $res=json_decode(Carousel::where('carouselid',$request->carouselid)->value('carouselimg'));
                $request->suolvetu=json_encode($res);
            }
            $res=Carousel::where('carouselid',$request->carouselid)->update([
                'position'=>$request->position,
                'title'=>$request->title,
                'carouselimg'=>$request->suolvetu,
                'url'=>$request->url,
            ]);
            if ($res !==false){
                return response()->json(['status'=>200,'msg'=>"修改成功"]);
            }else{
                return response()->json(['status'=>404,'msg'=>"修改失败"]);
            }
        }
    }

    public function edit(Request $request)
    {
        $data=Carousel::where('carouselid',$request->id)->first();
        $extend=Carousel_extend::all();

        return view('admin.carousel.add',['extend' =>$extend,'data'=>$data]);
    }

    public function del(Request $request)
    {
        if(Carousel::where('carouselid',$request->id)->delete()){
            return response()->json(['status' => '200', 'msg' =>'删除成功!',]);
        }else{
            return response()->json(['status' => '404', 'msg' =>'删除失败!',]);
        }
    }
}
