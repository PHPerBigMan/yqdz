<?php
namespace App\Http\Controllers\home;

use App\Models\Carriage;
use App\Models\Commodity;
use App\Models\Love;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsController extends Controller
{
    //查看商品详情
    public function detail(request $request){
        $uid=$_COOKIE['uid'];
        if (empty($uid)){
            return view('home.weixin.login');//跳转到登录
        }
        if(empty($request->id)){
            return response()->json(['msg'=>"缺少必要参数!"]);
        }
        $is_buy=User::where('uid',$uid)->value('isbuy');

        //判断是否存在分享
        if(!empty($request->fromto)){
            if($is_buy == 0){
                //未购买,带参数
                session('tuijian',$request->fromto);//下单的时候根据该值判断分成情况
                $from = explode("|",base64_decode($request->fromto));
                if(count($from)== 3 && (in_array($uid,$from) == false)){
                    //对数组进行删除第一个，增加当前用户ID
                    unset($from[0]);
                    array_push($from,$uid);
                }
            }else{
                //已经购买,带参数
                $level = array_reverse(User::where('uid',$uid)->select('first','second','three')->first());
                unset($level[0]);
                array_push($level,$this->uid);
                $from = $level;
            }
        }else{
           if ($is_buy == 0){
               if (is_null(session('tuijian'))){
                   $from = array("0","0","0");
               }else{
                   $from = explode("|",base64_decode(session('tuijian')));
                   if(count($from)== 3 && (in_array($uid,$from) == false)){
                       //对数组进行删除第一个，增加当前用户ID
                       unset($from[0]);
                       array_push($from,$uid);
                   }
               }
           }else{
                //已经购买,带参数
               $level = array_reverse(User::where('uid',$uid)->select('first','second','three')->first());
               unset($level[0]);
               array_push($level,$this->uid);
               $from = $level;
           }
        }
        $id=$request->id;
        //获取商品数据
        $data=Commodity::where('commodityid',$id)->select('commodityid','title','thumbnail','money',
            'hostess','carrousel','content','sales','carriageid','number','appraisal','labelid')->first();
        if ($data['commodityid'] == false){
            return response()->json(['msg'=>"缺少必要参数!"]);
        }
        $data['carriage']=Carriage::where('carriageid',$data['carriageid'])->value('price');
        $data['carrousel']  = json_decode($data['carrousel']);
        $res=Love::where('uid',$uid)->where('commodityid',$data['commodityid'])->first();
        if (!empty($res)){
            $data['islove'] =1; //已经收藏
        }else{
            $data['islove'] =0;
        }
        $url = "Home/goods/detail?id=".$id."&fromto=".base64_encode(implode("|",$from));

        return view('home.goods.detail',['data'=>$data,'url'=>$url]);
    }

    //商品评价页面
    public function comment(Request $request){

    }
    //商品关注
    public function love(Request $request){
      // echo "21123";die;
        $uid=$_COOKIE['uid'];
       $data=Love::create([
           'uid'=>$uid,
            'commodityid'=>$request->commodity
        ]);
        if ($data){
            return response()->json(['status'=>200,'msg'=>"关注成功"]);
        }else{
            return response()->json(['status'=>404,'msg'=>"关注失败"]);
        }
    }
    public function cancelLove(Request $request){
      // echo "456";die;
        $uid=$_COOKIE['uid'];
       $data=Love::where([
           'uid'=>$uid,
            'commodityid'=>$request->commodity
        ])->delete();
        if ($data){
            return response()->json(['status'=>200,'msg'=>"取消关注成功"]);
        }else{
            return response()->json(['status'=>404,'msg'=>"取消关注失败"]);
        }
    }
    
}
