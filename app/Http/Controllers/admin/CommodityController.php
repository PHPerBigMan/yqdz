<?php

namespace App\Http\Controllers\admin;

use App\Models\Carousel;
use App\Models\Carriage;
use App\Models\Classify;
use App\Models\Commodity;
use App\Models\Commodity_dt;
use App\Models\Commodity_comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommodityController extends Controller
{
    public function lists(Request $request){

        if($request->type == "1"){
            $data=Commodity::where('title','like','%'.$request->keyword.'%')->orderBy('hot_order','asc')->paginate(10);
        }elseif ($request->type =="2"){
            $res=Classify::where('name','like','%'.$request->keyword.'%')->first();
            $data=Commodity::where('classifyid',$res['classifyid'])->orderBy('hot_order','asc')->paginate(10);
        }else{
            $data=Commodity::orderBy('hot_order','asc')->paginate(10);
        }
        return view('admin.commodity.lists',['data'=>$data,'type'=>$request->type,'keyword'=>$request->keyword]);
    }

    public function add(){
        $carriage=Carriage::all();
        $classify=Classify::all();
//        $dt=Commodity_dt::first();


        return view('admin.commodity.add',['carriage'=>$carriage,'classify'=>$classify]);
    }

    public function edit(Request $request){
        $carriage=Carriage::all();
        $commodity=Commodity::where('commodityid',$request->id)->first();
        $classify=Classify::all();
//        $commodity['labelid']=json_decode($commodity['labelid']);
        $commodity['content']=html_entity_decode($commodity['content']);
        $dt=Commodity_dt::where('goods_id',$request->id)->first();
        if(empty($commodity->labelid)){
            $commodity->labelid=array();
        }
        $commodity->thumbnail = json_decode($commodity->thumbnail);
        $commodity->dz_thumbnail = json_decode($commodity->dz_thumbnail);
        $commodity->fx_thumb = json_decode($commodity->fx_thumb);
//        dd($carriage);
//        $commodity['appraisal']=html_entity_decode($commodity['appraisal']);
//        dd($commodity->carrousel);
        return view('admin.commodity.add',['carriage'=>$carriage,'classify'=>$classify,'commodity'=>$commodity,'dt'=>$dt]);
    }

    public function handle(Request $request){
//        dd($request);
//        dd(date('Y-m-d H:i:s'));
        if($request->classifyid == 0){
            return response()->json(['status'=>404,'msg'=>"商品类别必须选择一个"]);
        }
        if($request->number>$request->stock){
            return response()->json(['status'=>404,'msg'=>"目标数量不能大于库存数量"]);
        }

        if($request->starttime <date('Y-m-d')){
            return response()->json(['status'=>404,'msg'=>"开始时间不能小于今天"]);
        }
        if($request->starttime >$request->endtime){
            return response()->json(['status'=>404,'msg'=>"开始时间不能大于结束时间"]);
        }
        if(empty($request->content)){
            return response()->json(['status'=>404,'msg'=>"请填写图文详情"]);
        }
        if($request->money<=0){
            return response()->json(['status'=>404,'msg'=>"商品价格必须大于0"]);
        }
        if($request->number<0){
            return response()->json(['status'=>404,'msg'=>"目标数量必须大于等于0"]);
        }
        if($request->stock<1){
            return response()->json(['status'=>404,'msg'=>"商品库存数量必须大于1"]);
        }
//        'firstgraded'=>$request->firstgraded,
//                'secondgraded'=>$request->secondgraded,
//                'threegraded'=>$request->threegraded,
//            
        // dd($request->firstgraded);
        if($request->firstgraded<0){
            return response()->json(['status'=>404,'msg'=>"一级提成比例必须大于等于0"]);
        }elseif ($request->firstgraded>=100){
            return response()->json(['status'=>404,'msg'=>"一级提成比例必须小于100"]);
        }
        if($request->secondgraded<0){
            return response()->json(['status'=>404,'msg'=>"二级提成比例必须大于等于0"]);
        }elseif ($request->secondgraded>=100){
            return response()->json(['status'=>404,'msg'=>"二级提成比例必须小于100"]);
        }
        if($request->threegraded<0){
            return response()->json(['status'=>404,'msg'=>"三级提成比例必须大于等于0"]);
        }elseif ($request->threegraded>=100){
            return response()->json(['status'=>404,'msg'=>"三级提成比例必须小于100"]);
        }
        if($request->firstgraded+$request->secondgraded+$request->threegraded>=100){
            return response()->json(['status'=>404,'msg'=>"提成比例超出限制"]);
        }


        if($request->recommended){
            if(!$request->recom_order){
                return response()->json(['status'=>404,'msg'=>"请填写精选排序"]);
            }
        }
        if($request->is_hot){
            if(!$request->hot_order){
                return response()->json(['status'=>404,'msg'=>"请填写推荐排序"]);
            }
        }
        if (empty($request->commodityid)){

            //    hongwenyang  新增
            if($request->recom_order){
                //如果存在相同的排序 则这之后的排序都自增 1
                $ifHas = Commodity::where('recom_order',$request->recom_order)->value('commodityid');

                if($ifHas){
                    Commodity::where('recom_order','>',$request->recom_order -1)->increment('recom_order');
                }else{
                    // 如果没有
                    $request->recom_order =  (Commodity::orderBy('recom_order','desc')->value('recom_order')) + 1;
                }
            }else{
                //没有填写 排序
                $request->recom_order =  (Commodity::orderBy('recom_order','desc')->value('recom_order')) + 1;
//                $request->recom_order =  Commodity::orderBy('recom_order','desc')->value('recom_order');
            }
            if($request->hot_order){
                $ifHas = Commodity::where('hot_order',$request->hot_order)->value('commodityid');

                if($ifHas){
                    Commodity::where('hot_order','>',$request->hot_order -1)->increment('hot_order');
                }else{
                    $request->hot_order =  (Commodity::orderBy('hot_order','desc')->value('hot_order')) + 1;
                }
            }else{
                //没有填写 排序
                $request->hot_order =  (Commodity::orderBy('hot_order','desc')->value('hot_order')) + 1;
            }

            // if(empty($request->lunbo)){
            //     return response()->json(['status'=>404,'msg'=>"请上传轮播图"]);
            // }
            // if(empty($request->appraisal)){
            //     return response()->json(['status'=>404,'msg'=>"请填写机构测评"]);
            // }
            $path=$request->file('suolvetu');
            $dzpath = $request->file('dz_suolvetu');
            if (!empty($path)){
                foreach ($path as $k => $v){
                    $pic[$k]='/'.$v->store('uploads','uploads');
                }
                $request->suolvetu=json_encode($pic);
            }else{
                return response()->json(['status'=>404,'msg'=>"请上传商品图片"]);
            }

            if (!empty($dzpath)){
                foreach ($dzpath as $k => $v){
                    //首页产品缩略图
                    $pic[$k]='/'.$v->store('uploads','uploads');
                }
                $request->dz_suolvetu=json_encode($pic);
            }else{
                return response()->json(['status'=>404,'msg'=>"请上传商品图片"]);
            }
            $path2=$request->file('fx_thumb');
            if($path2){
                foreach ($path2 as $k => $v){
                    $pic2[$k]='/'.$v->store('uploads','uploads');
                }
                $request->fx_thumb=json_encode($pic2);
            }else{
                $request->fx_thumb='';
            }

           

            $lunbo=$request->file('lunbo');

            if (!empty($lunbo)){
                foreach ($lunbo as $k =>$v){
                    $lb[$k]='/'.$v->store('uploads','uploads');
                }
                $request->lunbo=json_encode($lb);
            }
            $id=Commodity::insertGetId([
                'nickname'=>$request->nickname,
                'classifyid'=>$request->classifyid,
                'title'=>$request->title,
                'describes'=>$request->describes,
                'thumbnail'=>$request->suolvetu,
                'dz_thumbnail'=>$request->dz_suolvetu,
                'fx_thumb'=>$request->fx_thumb,
                'money'=>$request->money,
                'experts'=>$request->experts,
                'content'=>$request->content,
                'appraisal'=>$request->appraisal,
                'carrousel'=>$request->lunbo,
                'number'=>$request->number,
                'stock'=>$request->stock,
                'weight'=>$request->weight,
                'starttime'=>$request->starttime,
                'endtime'=>$request->endtime,
                'firstgraded'=>$request->firstgraded,
                'secondgraded'=>$request->secondgraded,
                'threegraded'=>$request->threegraded,
                'recommended'=>$request->recommended,
                'recom_order'=>$request->recom_order,
                'is_hot'=>$request->is_hot,
                'hot_order'=>$request->hot_order,
                'carriageid'=>$request->carriageid,
                'labelid'=>json_encode($request->labelid)
            ]);
            if($request->d_content && $request->d_starttime){
                $d_lunbo=$request->file('d_lunbo');
                if (!empty($d_lunbo)){
                    foreach ($d_lunbo as $k =>$v){
                        $d_lb[$k]='/'.$v->store('uploads','uploads');
                    }
                    $request->d_lunbo=json_encode($d_lb);
                }
                $d_id=Commodity_dt::create([
                    'goods_id'=>$id,
                    'content'=>$request->d_content,
                    'img'=>$request->d_lunbo,
                    'create_time'=>$request->d_starttime
                ]);


            }

            if($id){
                return response()->json(['status'=>200,'msg'=>"添加成功"]);
            }else{
                return response()->json(['status'=>404,'msg'=>"添加失败"]);
            }
        }else{

            if($request->recom_order){
                // 查询 该排序是否已被使用 (老)
                $ifHas = Commodity::where('recom_order',$request->recom_order)->value('commodityid');

                if($ifHas){
                    // 如果已使用 在 查询到的和该排序内的 产品顺序后延
                    // 获取 这两者之间排序的 所有商品
                    // 获取当前商品的排序


                    $nowRecomOrder = Commodity::where('commodityid',$request->commodityid)->value('recom_order');
                    if($nowRecomOrder > $request->recom_order){
                        $min = $request->recom_order;
                        $max = $nowRecomOrder;
                    }else{
                        $min = $nowRecomOrder;
                        $max = $request->recom_order;
                    }

                    Commodity::whereBetween('recom_order',[$min,$max])->increment('recom_order');
                }
            }
            if($request->hot_order){
                $ifHas = Commodity::where('hot_order',$request->hot_order)->value('commodityid');

                if($ifHas){

                    $nowHotOrder = Commodity::where('commodityid',$request->commodityid)->value('hot_order');
                    if($nowHotOrder > $request->hot_order){
                        $min = $request->hot_order;
                        $max = $nowHotOrder;
                    }else{
                        $min = $nowHotOrder;
                        $max = $request->hot_order;
                    }
                    Commodity::whereBetween('hot_order',[$min,$max])->increment('hot_order');
                }
            }

            $path=$request->file('suolvetu');
            $dzpath = $request->file('dz_suolvetu');
            if (!empty($path)){
                foreach ($path as $k => $v){
                    $pic[$k]='/'.$v->store('uploads','uploads');
                }
                $request->suolvetu=json_encode($pic);
            }else{
                $res=Commodity::where('commodityid',$request->commodityid)->value('thumbnail');
                if(empty($res)){
                    return response()->json(['status'=>404,'msg'=>"请上传商品图片"]);
                }else{
                    $request->suolvetu=$res;
                }
            }

            if (!empty($dzpath)){
                foreach ($dzpath as $k => $v){
                    //首页产品缩略图
                    $pic[$k]='/'.$v->store('uploads','uploads');
                }
                $request->dz_suolvetu=json_encode($pic);
            }else{
                $res = Commodity::where('commodityid',$request->commodityid)->value('dz_thumbnail');
                if(empty($res)){
                    return response()->json(['status'=>404,'msg'=>"请上传商品图片"]);
                }else{
                    $request->dz_suolvetu= $res;
                }
            }
            $path2=$request->file('fx_thumb');
            if($path2){
                foreach ($path2 as $k => $v){
                    $pic2[$k]='/'.$v->store('uploads','uploads');
                }
                $request->fx_thumb=json_encode($pic2);
            }else{
                $res = Commodity::where('commodityid',$request->commodityid)->value('fx_thumb') ;
                if(empty($res)){
                    return response()->json(['status'=>404,'msg'=>"请上传商品图片"]);
                }else{

                    $request->fx_thumb= $res;
                }
            }
            
            $res=json_decode(Commodity::where('commodityid',$request->commodityid)->value('carrousel'),true);
            $lunbo=$request->file('lunbo');
            if(empty($res)){
                $res=array();
            }
            if (!empty($lunbo)){
                foreach ($lunbo as $k =>$v){
                    $lb[$k]='/'.$v->store('uploads','uploads');
                }
                $lb = array_merge($lb,$res);
                $request->lunbo=json_encode($lb);
            }else{
                $request->lunbo=json_encode($request->xcimg);
            }

            $list=Commodity::where('commodityid',$request->commodityid)->update([
                'nickname'=>$request->nickname,
                'classifyid'=>$request->classifyid,
                'title'=>$request->title,
                'describes'=>$request->describes,
                'thumbnail'=>$request->suolvetu,
                'dz_thumbnail'=>$request->dz_suolvetu,
                'fx_thumb'=>$request->fx_thumb,
                'money'=>$request->money,
                'content'=>$request->content,
                'experts'=>$request->experts,
                'appraisal'=>$request->appraisal,
                'number'=>$request->number,
                'stock'=>$request->stock,
                'weight'=>$request->weight,
                'carrousel'=>$request->lunbo,
                'starttime'=>$request->starttime,
                'endtime'=>$request->endtime,
                'firstgraded'=>$request->firstgraded,
                'secondgraded'=>$request->secondgraded,
                'threegraded'=>$request->threegraded,
                'recommended'=>$request->recommended,
                'recom_order'=>$request->recom_order,
                'is_hot'=>$request->is_hot,
                'hot_order'=>$request->hot_order,
                'carriageid'=>$request->carriageid,
                'labelid'=>json_encode($request->labelid)
            ]);

            if($request->d_content && $request->d_starttime){

                $d_lunbo=$request->file('d_lunbo');
                if (!empty($d_lunbo)){
                    foreach ($d_lunbo as $k =>$v){
                        $d_lb[$k]='/'.$v->store('uploads','uploads');
                    }
                    $request->d_lunbo=json_encode($d_lb);
                }else{
                    $request->d_lunbo=json_encode($request->d_xcimg);
                }
//                dd($request->d_lunbo);
                $commodity_dt=Commodity_dt::where('goods_id',$request->commodityid)->count();
                if($commodity_dt){
                    $id=Commodity_dt::where('goods_id',$request->commodityid)->update([
                        'goods_id'=>$request->commodityid,
                        'content'=>$request->d_content,
                        'img'=>$request->d_lunbo,
                        'create_time'=>$request->d_starttime
                    ]);
                }else{
                    $d_id=Commodity_dt::create([
                        'goods_id'=>$request->commodityid,
                        'content'=>$request->d_content,
                        'img'=>$request->d_lunbo,
                        'create_time'=>$request->d_starttime
                    ]);
                }




            }
            if ($list !== false){
                return response()->json(['status'=>200,'msg'=>"修改成功"]);
            }else{
                return response()->json(['status'=>404,'msg'=>"修改失败"]);
            }
        }
    }

    public function del(Request $request){
        // 查询改商品的精选排名和 推荐排序
        $recom_order = Commodity::where('commodityid',$request->id)->select('recom_order','hot_order')->first();
        // 删除后修改其他商品的排序
        Commodity::where('recom_order','>',$recom_order->recom_order)->decrement('recom_order');
        Commodity::where('hot_order','>',$recom_order->hot_order)->decrement('hot_order');
        if(Commodity::where('commodityid',$request->id)->delete()){

            return response()->json(['status' => '200', 'msg' =>'删除成功!',]);
        }else{
            return response()->json(['status' => '404', 'msg' =>'删除失败!',]);
        }
    }

    public function remove(Request $request){
        if($request->status == 0){
            $temp=1;
        }else{
            $temp=0;
        }
        $temps=$temp;
        if(Commodity::where('commodityid',$request->id)->update(['status'=>$temps])){
            return response()->json(['status' => '200', 'msg' =>'操作成功!',]);
        }else{
            return response()->json(['status' => '404', 'msg' =>'操作失败!',]);
        }
    }
    public function removes(Request $request){
        if($request->past == 0){
            $temp=1;
        }else{
            $temp=0;
        }
        $temps=$temp;
        if(Commodity::where('commodityid',$request->id)->update(['past'=>$temps])){
            return response()->json(['status' => '200', 'msg' =>'操作成功!',]);
        }else{
            return response()->json(['status' => '404', 'msg' =>'操作失败!',]);
        }
    }
    //ajax 删除图片
    public function ajaxDelPics(Request $request){
        $goods=Commodity::where('commodityid',$request->id)->first();
        $goods->carrousel=json_decode($goods->carrousel,true);
        $arr=$goods->carrousel;
        foreach ( $arr as $key => $val) {
            if($val==$request->pic){
//                echo 123456;
                unset($arr[$key]);
//                dd($key);
            }
        }
//        dd($arr);
        $arr=json_encode($arr);
        Commodity::where('commodityid',$request->id)->update(['carrousel'=>$arr]);
//        dd($arr);
    }
    public function d_ajaxDelPics(Request $request){
        $goods=Commodity_dt::where('goods_id',$request->id)->first();
//        dd($goods);
        $goods->img=json_decode($goods->img,true);
        $arr=$goods->img;
        foreach ( $arr as $key => $val) {
            if($val==$request->pic){
//                echo 123456;
                unset($arr[$key]);
//                dd($key);
            }
        }
//        dd($arr);
        $arr=json_encode($arr);
        Commodity_dt::where('goods_id',$request->id)->update(['img'=>$arr]);
//        dd($arr);
    }
    //ajax 删除标签
    public function ajaxDelLabel(Request $request){
        $goods=Commodity::where('commodityid',$request->id)->first();
        $goods->labelid=json_decode($goods->labelid,true);
        $arr=$goods->labelid;
        foreach ( $arr as $key => $val) {
            if($val==$request->label){
//                echo 123456;
                unset($arr[$key]);
//                dd($key);
            }
        }
//        dd($arr);
        $arr=json_encode($arr);
        Commodity::where('commodityid',$request->id)->update(['labelid'=>$arr]);
//        dd($arr);
    }

}
