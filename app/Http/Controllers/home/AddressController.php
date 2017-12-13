<?php

namespace App\Http\Controllers\home;

use App\Models\Address;
use App\Models\Carriage;
use App\Models\Cart;
use App\Models\Commodity;
use App\Models\Order;
use App\Models\Order_commodity_snop;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddressController extends Controller
{
    //获取某一用户默认收货地址
    public function get_one()
    {
        $uid =$_COOKIE['uid'];
        $data=Address::where('uid',$uid)->where('is_default',1)->first();
        return response()->json(['data'=>$data]);
    }

    //获取用户所有收货地址列表
    public function get_all()
    {
        $uid = $_COOKIE['uid'];
        $data = Address::where('uid', $uid)->get();
        return response()->json(['data' => $data]);
    }

    //新增用户收货地址
    public function add(Request $request){
        // dump($request);die;
        $uid=$_COOKIE['uid'];
        // dump($request->is_default);die;
        if ($request->is_default=='yes') {
            $is_default=1;
        }else{
            if(Address::where('uid',$uid)->count()==0){
                $is_default=1;
            }else{
                $is_default=0;
            }
        }
        if($is_default==1){
            $res=Address::where('uid',$uid)->update(['is_default'=>0]);
        }
        $address1=explode('-',$request->address1);
        $province=$address1[0];
        $city=$address1[1];
        $district=$address1[2];
        if($request->type == 1){
            // 地址管理页面
            $type = 1;
        }else{
            $type = 0;
        }
        if($type == 1){
            $id=Address::create(
                array(
                    'is_default'=>$is_default,
                    'name'=>$request->name,
                    'phone'=>$request->phone,
                    'province'=>$province,
                    'city'=>$city,
                    'district'=>$district,
                    'address'=>$request->address,
                    'uid'=>$uid
                )
            );
        }else{
            Address::where('uid',$uid)->update([
                'is_select'=>0
            ]);

            $id=Address::create(
                array(
                    'is_default'=>0,
                    'is_select'=>1,
                    'name'=>$request->name,
                    'phone'=>$request->phone,
                    'province'=>$province,
                    'city'=>$city,
                    'district'=>$district,
                    'address'=>$request->address,
                    'uid'=>$uid
                )
            );
        }
        $orderid = 0;
        if(isset($_GET['orderid'])){
            $orderid = $_GET['orderid'];
        }else{
            $orderid = $request->orderid;
        }

        if ($id) {
            if(Address::where('uid',$uid)->count() <=0){
                //判断是不是第一个
                Address::where('uid',$uid)->update(['is_default'=>1]);
            }
            if($request->id){
//                header('Location:/goods-order?id=\'.$request->id.\'&num=\'.$request->num.\'&money=\'.$request->money');
                if($type == 1){
                    return redirect('/address?type=1')->with('message', '添加失败!');
                }else{
                    return redirect('/goods-order?id='.$request->id.'&num='.$request->num.'&orderid='.$orderid);
                }
            }else{
                return redirect('/address?type=1')->with('message', '添加失败!');
            }
//            echo "<script>history.go(-3)</script>>";
        }else{
            if($type == 1){
                return redirect('/address?type=1')->with('message', '添加失败!');
            }else{
                return redirect('/goods-order?id='.$request->id.'&num='.$request->num.'&orderid='.$orderid);
            }
        }

    }
    //修改用户收货地址
    public function edit(Request $request){
        // dump(123);die;
         // dump($request->id);die;
        $orderid = 0;
        $uid=$_COOKIE['uid'];
        // dump($request->is_default);die;
        if ($request->is_default=='yes' || $request->is_default==1) {
            $is_default=1;
        }else{
            $is_default=0;
        }
        $address1=explode('-',$request->address1);
        $province=$address1[0];
        $city=$address1[1];
        $district=$address1[2];
        if($request->type == 1){
            // 地址管理页面
            $type = 1;
        }else{
            $type = 0;
        }
        Address::where('uid',$uid)->update(['is_default'=>0]);
        if($type == 1){
            $id=Address::where(array('addressid'=>$request->addressid,'uid'=>$uid))->update(
                array(
                    'is_default'=>$is_default,
                    'name'=>$request->name,
                    'phone'=>$request->phone,
                    'province'=>$province,
                    'city'=>$city,
                    'district'=>$district,
                    'address'=>$request->address,
                )
            );
        }else{
            Address::where('uid',$uid)->update([
                'is_select'=>0
            ]);

            $id=Address::where(array('addressid'=>$request->addressid,'uid'=>$uid))->update(
                array(
                    'is_default'=>0,
                    'is_select'=>1,
                    'name'=>$request->name,
                    'phone'=>$request->phone,
                    'province'=>$province,
                    'city'=>$city,
                    'district'=>$district,
                    'address'=>$request->address,
                )
            );
        }

        if(isset($_GET['orderid'])){
            $orderid = $_GET['orderid'];
        }else{
            $orderid = $request->orderid;
        }

        if ($id) {
            if(Address::where('uid',$uid)->count() <=0){
                //判断是不是第一个
                Address::where('uid',$uid)->update(['is_default'=>1]);
            }
            if($type == 0){
                return redirect('/goods-order?id='.$request->id.'&num='.$request->num.'&orderid='.$orderid)->with('message', '修改成功!');
            }else{
                return redirect('/address?type=1')->with('message', '修改成功!');
            }
            // return response()->json(['status'=>200,'msg'=>"修改成功！"]);
        }else{
            if($type == 0){
                return redirect('/goods-order?id='.$request->id.'&num='.$request->num.'&orderid='.$orderid)->with('message', '修改失败!');
            }else{
                return redirect('/address?type=1')->with('message', '修改成功!');
            }
        }
    }
    //收货地址管理列表
    public function lists(){
        $uid=$_COOKIE['uid'];
        $data=Address::where('uid',$uid)->get();
        return view('home.address.lists',['data'=>$data]);
    }
    //删除收货地址
    public function del(Request $request){
//         echo "123";die;
        $uid=$_COOKIE['uid'];
        if (Address::where(array('addressid'=>$request->addressid,'uid'=>$uid))->delete()){
            return redirect('/address?id='.$request->id.'&num='.$request->num)->with('message', '删除成功!');
        }else{
            return redirect('/address?id='.$request->id.'&num='.$request->num)->with('message', '删除失败!');
        }
    }
    //设置默认地址
    public function setdefault(Request $request){
//         dump($request);die;
        $uid=$_COOKIE['uid'];
        $res=Address::where('uid',$uid)->update(['is_default'=>0]);
        // dump($res);die;
        $resl=Address::where(array('addressid'=>$request->addressid,'uid'=>$uid))->update(['is_default'=>1]);
//         dump($resl);die;
        if($request->type){
            // 我的地址进行修改
            return redirect('/address?type=1')->with('message', '设置成功!');
        }else{
            // 订单页
            if($res >=1 || $resl >=1){
                return redirect('/goods-order?id='.$request->id.'&num='.$request->num)->with('message', '设置成功!');
            }else{
                return redirect('/goods-order?id='.$request->id.'&num='.$request->num)->with('message', '设置失败!');
            }
        }
    }
    //更新订单运费价格 
//    public function cale(Request $request){
//
//        $order=Order::where('orderid',$request->id)->first();
//        if($order['commercial'] != ''){
//            return response()->json(['status'=>404,'msg'=>"已经下单过的订单无法修改地址！"]);
//        }
//
//        $address=Address::where('addressid',$request->addressid)->first();
//        $snop=Order_commodity_snop::where('orderid',$request->id)->first();
//        $snop['snopjson'] = json_decode($snop['snopjson'],true);
//        $carriageid = $snop['snopjson']['carriageid'];
//
//        $data=Carriage::where('carriageid',$carriageid)->first();//获取运费价格
//        if($address == "0"){
//            $carriage= $data['price'];
//        }else{
//            foreach ($data['extend'] as $k => $v){
//                if($v['takeprovince'] == $address['province']){
//                    return $v['price'];
//                }
//            }
//            $carriage=$data['price'];
//        }
//
//
//        $data=Order::where('orderid',$request->id)->first();
//        //重新下单
//
//        $uniqueid = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
////        $commercial = A('weixin')->createorder($snop['snopjson']['title'],$save['uniqueid'],$data['money'] + $carriage);//生成微信支付订单,这里是金额
//
//        $money = $data['money'] + $carriage;
//
////        $config = A('weixin')->zfjs($save['commercial']);
//
//        M('order')->where(array('orderid'=>$id))->save($save);
//        Order::where('orderid',$request->id)->update([
//            'carriage'=>$carriage,
//            'addressid'=>$request->addressid,
//            'uniqueid'=>$uniqueid,
//            'commercial'=>$commercial
//        ]);
//
////        $this->ajaxReturn(compact('money','config'));
//        return response()->json(['money'=>$money,'config'=>$config]);
//    }


    public function cale(Request $request){
        $addressid=$request->addressid;
        $goods_id=$request->commodityid;
//        $num=$request->num;
        //商品ID切割字符串,并去空
        $goods_id=explode(',',$goods_id);
        foreach ($goods_id as $key => $val) {
            if(empty($val)){
                unset($goods_id[$key]);
            }
        }
        //商品ID切割字符串,并去空
//        $num=explode(',',$num);
//        foreach ($num as $key => $val) {
//            if(empty($val)){
//                unset($num[$key]);
//            }
//        }

        // 从购物车内获取商品的数量

//        dd($num);
//        dd($goods_id);
        //查询地址
        $address=Address::where('addressid',$addressid)->first();
        //赋值地址
        $province=$address->province;
        //地址过滤
        $province=str_replace("市","",$province);
        $province=str_replace("省","",$province);
        $province=str_replace("壮族自治区","",$province);
        $province=str_replace("自治区","",$province);
        $province=str_replace("回族自治区","",$province);
        $province=str_replace("特别行政区","",$province);
//        dump($province)

        $goods=Commodity::whereIn('commodityid',$goods_id)->orderBy('commodityid','desc')->get();
        foreach ($goods as $key => $val) {
            $num[$key] = Cart::where([
                'uid'=>$_COOKIE['uid'],
                'commodityid'=>$val->commodityid
            ])->value('nums');
        }
        //初始化运费
        $money = [];
        //循环商品取运费
        $notitle = array();
        //这一块说不定还有问题
        $provinceList = array();
        foreach ($goods as $key => $val) {
            $carriage[$key]=Carriage::where('carriage.carriageid',$val->carriageid)
                ->join('carriage_extend','carriage.carriageid','=','carriage_extend.carriageid')
                ->select('carriage.*','carriage_extend.first_price','carriage_extend.takeprovince','carriage_extend.extra_price')
                ->get();
            // 省份数组

            if(!$carriage[$key]->isEmpty()){
                foreach ($carriage as $k => $v) {
                    foreach($v as $k1=>$v1){
                        $provinceList[$k][$k1] = $v1->takeprovince;
                    }
                }
            }
            if($carriage[$key]->isEmpty()){
                $mrmoney=Carriage::where('carriage.carriageid',$val->carriageid)->value('price');
                $money+= $mrmoney;

            }else{
                foreach ($carriage as $k => $v) {
                    foreach($v as $k1=>$v1){
                        if(!in_array($province,$provinceList[$k])){
                            $notitle[$key] = $goods[$key]->title;
                        }else if($province == $v1->takeprovince){
                            //计算公式为:首重价格+续重价格*(重量*数量-1);
                            $nums=$num[$key];
                            if(($val->weight*$nums-1) <= 0){
                                // 如果重量为 0 则额外重量则为 0kg
                                $extra_weight = 0;
                            }else{
                                $extra_weight = ceil($val->weight*$nums-1);
                            }

                            $money[$key] = $v1->first_price+$v1->extra_price*$extra_weight;
                            $strlen = strlen($val->title);
                            $carriageList[$key] = "<div class='address-detail cart-title' style='font-size: 12px'>".mb_substr($val->title,0,18)."<span style='float: right'>"."￥".$money[$key]."</span>"."</div>";
                            if($strlen > 18){
                                $carriageList[$key] = "<div class='address-detail cart-title' style='font-size: 12px'>".mb_substr($val->title,0,18)."...."."<span style='float: right'>"."￥".$money[$key]."</span>"."</div>";
                            }
                        }
                    }
                }
            }
        }
        $allMoney = 0;
        foreach($money as $k=>$v){
            $allMoney += $v;
        }
//        $allMoney = $money[1];
        if(empty($notitle)){
            return response()->json(['money'=>$allMoney,'status'=>200,'carriageList'=>$carriageList]);
        }else{
            sort($notitle);
            return response()->json(['title'=>$notitle,'status'=>404]);
        }

    }


    /*
     * 开发者:Houjiacheng
     * 方法功能:设置选择地址
     * */
    public function set_address_select(Request $request){
        $orderid = 0;
//        dd($request->addressid);
        //先清空所有的地址选择
        $uid = $_COOKIE['uid'];
        Address::where('uid',$uid)->update(['is_select'=>0]);
        $address=Address::where('addressid',$request->addressid)->update(['is_select'=>1]);
        $orderid = $_GET['orderid'];
        if($address){
            return redirect('/goods-order?id='.$request->id.'&num='.$request->num.'&orderid='.$orderid)->with('message', '设置成功!');
        }else{
            return redirect('/goods-order?id='.$request->id.'&num='.$request->num.'&orderid='.$orderid)->with('message', '设置失败!');
        }
    }
}
