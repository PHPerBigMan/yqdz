<?php

namespace App\Http\Controllers\FrontEnd;

use App\Models\Carousel;
use App\Models\Order_extend;
use Cookie;
use App\Models\Commodity;
use App\Models\Commodity_comment;
use App\Models\Design;
use App\Models\User_msg;
use App\Models\Dividedinto;
use App\Models\Order;
use App\Models\Cart; 
use App\Models\Address;
use App\Models\Love;
use App\Models\Keyword;
use App\Models\Order_commodity_snop;
use App\Models\Commodity_dt;
use App\Models\Article;
use App\Models\Order_return_goods;
use App\Models\User;
use App\Models\User_msglist;
use App\Models\Classify;
use App\Models\Statistics;
use function dd;
use function EasyWeChat\Payment\get_client_ip;
use function explode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use EasyWeChat\Foundation\Application;
use function implode;
use function intval;
use function is_array;
use function redirect;
use function setcookie;
use function typeOf;
use PHPExcel;
use PHPExcel_Writer_Excel5;

class Page extends Controller
{
    // 首页
    public static $history;
    public function test(){
        $excel = new PHPExcel();
        //Excel表格式,这里简略写了8列
        $letter = array('A','B','C','D','E','F','F','G');
        //表头数组
        $tableheader = array('学号','姓名','性别','年龄','班级');
        //填充表头信息
        for($i = 0;$i < count($tableheader);$i++) {
            $excel->getActiveSheet()->setCellValue("$letter[$i]1","$tableheader[$i]");
        }
        //表格数组
        $data = array(
            array('1','小王','男','20','100'),
            array('2','小李','男','20','101'),
            array('3','小张','女','20','102'),
            array('4','小赵','女','20','103')
        );
        //填充表格信息
        for ($i = 2;$i <= count($data) + 1;$i++) {
            $j = 0;
            foreach ($data[$i - 2] as $key=>$value) {
                $excel->getActiveSheet()->setCellValue("$letter[$j]$i","$value");
                $j++;
            }
        }
        //创建Excel输入对象
        $write = new PHPExcel_Writer_Excel5($excel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename="testdata.xls"');
        header("Content-Transfer-Encoding:binary");
        $write->save('php://output');
    }
    public function demo(){
        echo 123456;
    }
    public function home()
    { 
        // dd('系统正在维护,维护时间为10:00-6:00,敬请期待!');
//        $user=User::where('uid',$_COOKIE['uid'])->select('first','second','three')->first();
        $ip = get_client_ip();
//        $ip=$request->getClientIp();
//        $ip=get_client_ip();
        DB::table('statistics')->where('visitor_nub', $ip)->increment('browse_nub', 1);
        if ($ip) {
            //查询IP是否记录
            $id = Statistics::where('visitor_nub', $ip)->value('id');
//            dd($id);
            //如果没有记录,则新增
            if (!$id) {
                Statistics::create([
                    'visitor_nub' => $ip,
                    'created_at' => date('Y-m-d')
                ]);
                DB::table('statistics')->where('visitor_nub', $ip)->increment('browse_nub', 1);
            }

        }
        //刷新页面浏览量+1
        $user = User::where('openid', @$_COOKIE['openid'])->count();
        //这是第一个判断,在首页的
        if (!$user) {
            include('wechat.php');
            $wechatObj->getAuthUrl(0,0,0,0,0);
        }
        //首页轮播图片
        $lunbo = Carousel::where('position', 1)->get();
        // dd($lunbo);
        foreach ($lunbo as $k => $v) {
            $lunbo[$k]['carouselimg'] = json_decode($v['carouselimg'], true);
            $lunbo[$k]['carouselimg'] = $lunbo[$k]['carouselimg'][0];
        }
        //首页广告图片
        // $advertising = Carousel::where('position', 1)->first();

        // $advertising->carouselimg = json_decode($advertising->carouselimg, true);
        // $advertising->carouselimg = $advertising->carouselimg[0];
        //首页notice
        // $notice = Weixin_config::where('name', 'notice')->value('value');
		$notice='共同购买，一起生活';
        //推荐商品
        $commodity = Commodity::where(array('is_hot'=>1,'past'=>0,'status'=>1))->orderBy('hot_order','asc')->get();
        foreach ($commodity as $k => $v) {
            $commodity[$k]['thumbnail'] = json_decode($v['thumbnail'], true);
            $commodity[$k]['thumbnail'] = $commodity[$k]['thumbnail'][0];
        }
        //往期商品
        $oldgoods = Commodity::where(array('past' => 1))->orderBy('endtime', 'desc')->limit(3)->get();
        foreach ($oldgoods as $k => $v) {
            $oldgoods[$k]['thumbnail'] = json_decode($v['thumbnail'], true);
            $oldgoods[$k]['thumbnail'] = $oldgoods[$k]['thumbnail'][0];
        }
        //支持的商品
        $hotesgoods = Design::where('status', 2)->orderBy('hotes', 'desc')->limit(3)->get();
        foreach($hotesgoods as $k=>$v){
            $v->suolve = $v->img[0];

        }
        foreach ($commodity as $key => $val) {
            if($val['labelid']=='null'){
                $val['labelid']=array();
//                $commodity[$key]=$val;
            }else{
                $val['labelid']=json_decode($val['labelid']);
            }

        }
//        dd(123456);
//        dd($commodity);
        $past = 0;
        $sort=classify::where('type',0)->get();
        return view('mobile.pages.home', ['lunbo' => $lunbo, 'notice' => $notice, 'oldgoods' => $oldgoods,
            'commodity' => $commodity, 'hotesgoods' => $hotesgoods,'sort'=>$sort,'past'=>$past,'id'=>0]);
    }

    // 搜索
    public function search()
    {
        $histoty=Keyword::where('uid',$_COOKIE['uid'])->get();
        return view('mobile.pages.search',['history'=>$histoty]);
    }
    // 删除搜索记录
    public function searchDelete()
    {
        DB::table('keyword')->truncate();
        return response()->json(['status'=>200,'msg'=>"删除记录成功"]);
    }

    // 搜索结果
    public function searchResult(request $request,$type='latest',$keyword='')
    {
        if($request->keyword){
            $keyword=$request->keyword;
            $type=$request->type;
        }
        // dump($request->type);die;
        switch ($type) {
            case 'latest': // 最新
                $type='starttime';
                $orderby='desc';
                break;
            case 'oldest': // 最老
                $type='starttime';
                $orderby='asc';
                break;
            case 'most': // 最多
                $type='hostess';
                $orderby='desc';
                break;
            case 'few': // 最少
                $type='hostess';
                $orderby='asc';
                break;
            case 'cheap': // 最便宜
                $type='money';
                $orderby='asc';
                break;
            case 'expensive': // 最贵
                $type='money';
                $orderby='desc';
                break;
        }
//        dd($type);
        if ($keyword) {
            $searchResult=Commodity::where('title', 'like', '%'.$keyword.'%')->where('status',1)->orderBy($type, $orderby)->get();
            foreach ($searchResult as $k => $v) {
                $searchResult[$k]['thumbnail'] = json_decode($v['thumbnail'], true);
                $searchResult[$k]['thumbnail'] = $searchResult[$k]['thumbnail'][0];
            }
            $keyword1=Keyword::where(array('uid'=>$_COOKIE['uid'],'keyword'=>$keyword))->value('keyword');
//            dd($keyword1);
            if($keyword1!=$keyword){
                Keyword::create([
                    'uid'=>$_COOKIE['uid'],
                    'keyword'=>$keyword
                ]);
            }
        }
//        dd($keyword1);
        return view('mobile.pages.searchResult', ['active' => $type,'searchResult'=>$searchResult,'keyword'=>$keyword]);
    }

    // 发起定制
    public function customSubmit()
    {
        $customCate=Classify::where('type',1)->get();

//         dd($customCate);
        // return response()->json(["status"=>200,"msg"=>"发布成功！"]);
        return view('mobile.pages.customOrder',['customCate' => $customCate]);
    }

    // 商品订单
    public function goodsSubmit()
    {
        return view('mobile.pages.goodsOrder');
    }

    public function enterpriseOrder()
    {

        //首页轮播图片
        $lunbo = Carousel::where('position', 1)->get();
        foreach ($lunbo as $k => $v) {
            $lunbo[$k]['carouselimg'] = json_decode($v['carouselimg'], true);
            $lunbo[$k]['carouselimg'] = $lunbo[$k]['carouselimg'][0];
        }
        return view('mobile.pages.enterpriseOrder', ['lunbo' => $lunbo]);
    }

    // 商品详情
    public function goodsDetail($id,$past=0)
    {

//        $uid=@$_COOKIE['uid'];
////        dd($uid);
//        if (empty(@$uid)) {
//            echo "<script>alert('首次购买,正在自动登录中...');</script>";
//            header("Location:http://yqdz.xs.sunday.so/");
//            die;
//        }
//        include('wechat.php');

        $user = User::where('openid', @$_COOKIE['openid'])->count();
        //这是第一个判断,在首页的
        if (!$user) {
            include('wechat.php');
            $wechatObj->getAuthUrl(3,$id,0,0,0);
        }
         //dd($_COOKIE,$_GET,$past);
//         dd(132456);
//        if(empty($_COOKIE['openid']) && $past==0){
//            $wechatObj->getAuthUrl(2,$id,0,0,0);
//        }

        if ($past==2){
            $appid="wxaeae5b0ab20a1524";//这里的appid是假的演示用
            $appsecret="8f67908ced38352c197caa3e8cdc6392";//这里的appsecret是假的演示用
            $code=$_GET['code'];
            $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$code."&grant_type=authorization_code ";
      
            //3.拉取用户的openid
            $res = $this->request($url,'get');
            $res=json_decode($res);
            // dd($res);
            $access_token=$res->access_token;
            $openid=$res->openid;
            $url="https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
            $userInfo=$this->request($url,'get');
            $userInfo=json_decode($userInfo);
            //添加用户到数据表
            $user=User::where('openid',$openid)->first();
            // dd($user);
            //如果没有查出数据,那么新增一条数据
            if(!$user){
//            dd(123465);
                $uid=User::insertGetId([
                    'openid'=>$openid,
                    'nickname'=>$userInfo->nickname,
                    'img'=>$userInfo->headimgurl,
                    'created_at'=>date('Y-m-d')
                ]);
//        dd('添加成功');
                setcookie("openid",$openid, time()+3600*24*365);
                setcookie("uid",$uid, time()+3600*24*365);
//            dd($_COOKIE['openid']);
                if(isset($_COOKIE['openid'])){
                    $userInfo=User::where('openid',$_COOKIE['openid'])->first();
//                dd($userInfo);
//                    return view('mobile.pages.user', ['userInfo' => $userInfo]);
                }
            }else{
                // dd($user);
                $userInfo=$user;
                // dd($user->openid);
                setcookie("openid",$user->openid, time()+3600*24*365);
                setcookie("uid",$user->uid, time()+3600*24*365);
                 // dd($_COOKIE,111);
//                return view('mobile.pages.user', ['userInfo' => $userInfo]);
            }


        }
        //判断是否有这个用户 
            //dd(isset($_COOKIE['openid'])?$_COOKIE['openid']:0);
        $user = User::where('openid',isset($_COOKIE['openid'])?$_COOKIE['openid']:'a')->first();
  //dd($user->toArray()); 
//        if (empty($user)){
//             // include('wechat.php');
//             $wechatObj->getAuthUrl(2,$id,0,0,0);
//        }
        $data = Commodity::where('commodityid', $id)->first();
        $endtime = strtotime($data['endtime']);
        $nowtime = time();
        if ($endtime - $nowtime > 0) {
            $time = ($endtime - $nowtime) / 86400;
            $times = ceil($time);
        } else {
            $times = 0;
        }
        //大家说信息
        $comment = Commodity_comment::where('commodityid', $id)->get();
        foreach ($comment as $k => $v) {
            $user = User::where('uid', $v['uid'])->first();
            $comment[$k]['nickname'] = $user['nickname'];
            $comment[$k]['img'] = $user['img'];
        }

        $data->thumbnail = json_decode($data->thumbnail, true);
        $data->thumbnail = $data->thumbnail[0];
        $data->fx_thumb = json_decode($data->fx_thumb, true);
        $data->fx_thumb = $data->fx_thumb[0];
        //关注
        
        $love=DB::table('love')->where(array('uid'=>@$_COOKIE['uid'],'commodityid'=>$data->commodityid))->value('commodityid');
        if (!$love) {
            $love=0;
        }
        // $goodsnop=serialize($data);
        // $love=json_encode($love);
        // dump($love);
        // $goodsnop=json_encode($data);
//        dd($data);
        //产品动态
        $dt=Commodity_dt::where('goods_id',$data->commodityid)->get();
        foreach ($dt as $k => $v) {
            $dt[$k]['img'] = json_decode($v['img'], true);
//            $dt[$k]['img'] = $dt[$k]['img'][0];
        }
//        foreach ($data as $key => $val) {
//            if($val['labelid']=='null'){
//                $val['labelid']=array();
////                $commodity[$key]=$val;
//            }else{
//                $val['labelid']=json_decode($val['labelid']);
//            }
//
//        }
        if($data->labelid=='null'){
            $data->labelid=array();
        }else{
            $data->labelid=json_decode($data->labelid);
        }
        $options = [
            // 前面的appid什么的也得保留哦
//            'debug'  => true,
            /**
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id' => 'wxaeae5b0ab20a1524',         // AppID
            'secret' => '8f67908ced38352c197caa3e8cdc6392',     // AppSecret
            'guzzle' => [
                'timeout' => 3.0, // 超时时间（秒）
                'verify' => false, // 关掉 SSL 认证（强烈不建议！！！）
            ],
        ];
        $app = new Application($options);
//        dd($options);
        $js = $app->js;
//        dd($js);
//        $user = User::where('uid', $uid)->first();
        $config = $js->config(array('onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareAppMessage','onMenuShareTimeline'));
       // dd($dt);
                // dd($_COOKIE);
        // dd($this->openid);
        return view('mobile.pages.goodsDetail', ['data' => $data, 'times' => $times, 'comment' => $comment,'love'=>$love,'past'=>$past,'dt'=>$dt,'config'=>$config]);
    }

    // 客服服务
    public function service()
    {
//        dd(1234156);
        $uid=$_COOKIE['uid'];
        $user=User::where('uid',$uid)->first();
        return view('mobile.pages.service', compact('user'));
    }

    // 定制广场
    public function square()
    {
        //顶部轮播图片
        $lunbo = Carousel::where('position', 1)->get();
        foreach ($lunbo as $k => $v) {
            $lunbo[$k]['carouselimg'] = json_decode($v['carouselimg'], true);
            $lunbo[$k]['carouselimg'] = $lunbo[$k]['carouselimg'][0];
        }
        //精选商品
        $commodity = Commodity::where(array('recommended'=>1,'past'=>0,'status'=>1))->orderBy('recom_order','asc')->get();
        foreach ($commodity as $k => $v) { 
            $commodity[$k]['thumbnail'] = json_decode($v['thumbnail'], true);
            $commodity[$k]['thumbnail'] = $commodity[$k]['thumbnail'][0];
            $commodity[$k]['dz_thumbnail'] = json_decode($v['dz_thumbnail'], true);
            $commodity[$k]['dz_thumbnail'] = $commodity[$k]['dz_thumbnail'][0];
        } 
        //支持的商品
        $hotesgoods = Design::where('status', 2)->orderBy('hotes', 'desc')->limit(3)->get();
        foreach ($hotesgoods as $k => $v) {
            $hotesgoods[$k]->suolve = $v->img[0];
        }

        return view('mobile.pages.square', ['lunbo' => $lunbo, 'commodity' => $commodity, 'hotesgoods' => $hotesgoods]);
    }

    // 个人中心 goodsList
//    public function user()
//    {
//
//    }
    public function evaluate($id)
    {
        dd('没有模板');
        return view('mobile.pages.evaluate');
    }
    function user(Request $request){
//         dd('后台正在维护,请稍等!');
        // dd('系统正在维护,维护时间为10:00-6:00,敬请期待!');
        //1.获取到code
//        dd($_COOKIE['uid']);
            $phone='15267098952';
//        $phone='123456789';
//            $request->session()->flush();
//          dd($request->session()->all());
//            $request->session()->pull('openid', 'default');
            $openid=$_COOKIE['openid'];
//            $openid='ocmSx1Qk93bRY_C0zQ031TKJ93iI';
//            dd(123456);
////            dd(count($openid));
////            if($openid['openid']){
            $userInfo=User::where('openid',$openid)->first();
////            }
//            dd($openid);
            //信息数量查询
            $msgCount=User_msglist::where(array('uid'=>$_COOKIE['uid'],'is_view'=>0))->count();
            //待付款数量查询
            $waitOrderCount=Order::where(array('uid'=>$_COOKIE['uid'],'order_state'=>10))->count();
            //待发货数量查询
            $waitSendCount=Order::where(array('uid'=>$_COOKIE['uid'],'order_state'=>20))->count();
//            dd($waitSendCount);
            //待收货数量查询
            $waitReceiptCount=Order::where(array('uid'=>$_COOKIE['uid'],'order_state'=>30))->count();
            //待评价数量查询
            $waitEvalCount=Order::where(array('uid'=>$_COOKIE['uid'],'evaluation_state'=>0,'order_state'=>50))->count();

            return view('mobile.pages.user', ['userInfo' => $userInfo,'phone'=>$phone,'msgCount'=>$msgCount,'waitOrderCount'=>$waitOrderCount,'waitSendCount'=>$waitSendCount,'waitReceiptCount'=>$waitReceiptCount,'waitEvalCount'=>$waitEvalCount]);

//                dd($openid);



                //清空用户openid
//


//            dd($userInfo);

//        dd($openid);
    }
    function getUserOpenId(request $request){

            //2.获取到网页授权的access_token
            $appid="wxaeae5b0ab20a1524";//这里的appid是假的演示用
            $appsecret="8f67908ced38352c197caa3e8cdc6392";//这里的appsecret是假的演示用
            $code=$_GET['code'];
            $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$code."&grant_type=authorization_code ";
//        dd($code);
            //3.拉取用户的openid
            $res = $this->request($url,'get');
            $res=json_decode($res);
            $access_token=$res->access_token;
            $openid=$res->openid;
            $url="https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
            $userInfo=$this->request($url,'get');
            $userInfo=json_decode($userInfo);
//        dd($userInfo);
            //添加用户到数据表
            $user=User::where('openid',$openid)->first();
//        dd($user);
            //如果没有查出数据,那么新增一条数据
            if(!$user){
//            dd(123465);
                $uid=User::insertGetId([
                    'openid'=>$openid,
                    'nickname'=>$userInfo->nickname,
                    'img'=>$userInfo->headimgurl,
                    'created_at'=>date('Y-m-d')
                ]);
//        dd('添加成功');
                setcookie("openid",$openid, time()+3600*24*365);
                setcookie("uid",$uid, time()+3600*24*365);
//            dd($_COOKIE['openid']);
                if(isset($_COOKIE['openid'])){
                    if(isset($request->id)){
                        return redirect("http://yqdz.xs.sunday.so/goods/".$request->id."/0");
                    }else{
                        $userInfo=User::where('openid',$_COOKIE['openid'])->first();
//                dd($userInfo);
                        return view('mobile.pages.user', ['userInfo' => $userInfo]);
                    }
                }

            }else{
                $userInfo=$user;
                setcookie("openid",$user->openid, time()+3600*24*365);
                setcookie("uid",$user->uid, time()+3600*24*365);
                if(isset($request->id)){
                    return redirect("http://yqdz.xs.sunday.so/goods/".$request->id."/0");
                }else{
                    return view('mobile.pages.user', ['userInfo' => $userInfo]);
                }
            }





    }


    // 请求接口的通用方法
    public function request($url,$method="get",$data=""){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        if($method == "post"){
            //POST请求的参数
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        }
        //忽略https的安全证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $rs = curl_exec($ch);
        curl_close($ch);
        return $rs;
    }
    // 定制商品/定制信息  列表
//    type: 0:最新发布 1:最多支持 2:价格
    public function goodsList($id = 0,$past = 0,$type = 0 )
    {
//        print_r($past);
        $sort=classify::where('type',0)->get();
        // dump($sort);
//        var_dump($id);
        $map=array();
        $orderby=array();
        switch ($type)
        {
            case 0:
                $orderby='starttime';
                break;
            case 1:
                $orderby='sales';
                break;
            case 2:
                $orderby='money';
                break;
        }

        if ($id){
          $map['classifyid']=$id;
        }
        if($past==1){
            $map['past']=1;
            $goodsList=Commodity::where($map)->orderBy($orderby,'desc')->get();
            foreach ($goodsList as $k => $v) {
                $goodsList[$k]['thumbnail'] = json_decode($v['thumbnail'], true);
                $goodsList[$k]['thumbnail'] = $goodsList[$k]['thumbnail'][0];
            }
        }else{
            $map['past']=0;
            $map['status']=1;
            $goodsList=Commodity::where($map)->orderBy($orderby,'desc')->get();
            foreach ($goodsList as $k => $v) {
                $goodsList[$k]['thumbnail'] = json_decode($v['thumbnail'], true);
                $goodsList[$k]['thumbnail'] = $goodsList[$k]['thumbnail'][0];
            }
        }
//        dd($past);
        return view('mobile.pages.goodsList', ['sort' => $sort, 'id' => $id,'goodsList'=>$goodsList,'type'=>$type,'past'=>$past]);
    }

    // 定制商品/定制信息  列表
    // 定制商品/定制信息  列表
//    type: 0:最新发布 1:最多支持 2:价格
    public function fundList($id = 0,$past = 0,$type = 0 )
    {
//        print_r($past);
        $sort=classify::where('type',1)->get();
//        var_dump($id);
        $map=array();
        $orderby=array();
        switch ($type)
        {
            case 0:
                $orderby='created_at';
                break;
            case 1:
                $orderby='hotes';
                break;
            case 2:
                $orderby='money';
                break;
        }
        $map['status']=2;
        if ($id){
            $map['cate_id']=$id;
        }
//        dd($orderby);
//         dump($orderby);
        $fundList=design::where($map)->orderBy($orderby,'desc')->get();
//        dd($fundList[0]->img[0]);
        foreach ($fundList as $k => $v) {
            $v->suolve = $v->img[0];
        }
        return view('mobile.pages.fundList', ['sort' => $sort, 'id' => $id,'fundList'=>$fundList,'type'=>$type]);
    }

    // 定制详情
    public function fundDetail($id)
    {
//        dd(123456);
        $data = Design::where('designid', $id)->first();
        $user=User::where('uid',$data->uid)->value('nickname');
        if($user){
            $data->nickname=$user;
        }else{
            $data->nickname='管理员';
        }
//        dd($data);
//        dd($data);
        $data->thumbnail = json_decode($data->thumbnail, true);
        $data->thumbnail = $data->thumbnail[0];

//        dump($data);die;
        return view('mobile.pages.fundDetail', ['data' => @$data]);
    }

    // 订单列表
    public function orderList($type = 'all')
    {
//        $uid=session('user.id');
        $uid=$_COOKIE['uid'];
        if ($type == "all") {
            $data = Order::where('uid', $uid)->select('transaction', 'order_state', 'addressid', 'orderid', 'money', 'carriage',
                'evaluation_state', 'refund_state', 'return_status','created_at')->with('snop')->orderBy('created_at', 'desc')->get()->toArray();
        } elseif ($type == "50") {
            $data = Order::where('uid', $uid)->where('order_state', $type)->where('evaluation_state', 0)->select('transaction', 'order_state', 'addressid', 'orderid', 'money', 'carriage',
                'evaluation_state', 'refund_state', 'return_status','created_at')->with('snop')->orderBy('created_at', 'desc')->get()->toArray();
        } elseif ($type == "10") {
            $data = Order::where('uid', $uid)->where('order_state', $type)->where('evaluation_state', 0)->select('transaction', 'order_state', 'addressid', 'orderid', 'money', 'carriage',
                'evaluation_state', 'refund_state', 'return_status','created_at')->with('snop')->orderBy('created_at', 'desc')->get()->toArray();
        } else {
//            dd($type);
            $data = Order::where('uid', $uid)->where('order_state', $type)->select('transaction', 'order_state', 'addressid', 'orderid', 'money', 'carriage',
                'evaluation_state', 'refund_state', 'return_status','created_at')->with('snop')->orderBy('created_at', 'desc')->get()->toArray();
//            dd($data);
        }
//        $data[$k]['snop']['snopArray'] = json_decode($v1['snopjson']);
//        $data[$k]['snop']['return_status'] = Order_return_goods::where('snopid', $v1['snopid'])->value('status');

        foreach ($data as $key => $val) {

            foreach($val['snop'] as $k=>$v){
                $data[$key]['snop'][$k]['snopjson']=json_decode($v['snopjson']);
                $data[$key]['snop'][$k]['return_status']=Order_return_goods::where('snopid', $v['snopid'])->value('status');
            }
//            dump($val->snop);
        }
//        $commodityid=trim($commodityid);
//        $num=trim($num);


        return view('mobile.pages.orderList', ['data' => $data,'type'=>$type]);
    }

    // 订单详情
    public function orderDetail($id)
    {
//        dd($id);
        $uid=$_COOKIE['uid'];
        $order=Order::where(array('uid'=>$uid,'orderid'=>$id))->first();
//        dump($id);
        $data = Order::where('uid', $uid)->where('orderid', $id)->select('uniqueid', 'order_state', 'addressid', 'orderid', 'money', 'carriage',
                'evaluation_state', 'refund_state', 'return_status')->with('snop')->orderBy('created_at', 'desc')->get()->toArray();
        foreach ($data as $key => $val) {
            $data[$key]['extend']=Order_extend::where('orderid',$id)->first();
            foreach($val['snop'] as $k=>$v){
                $data[$key]['snop'][$k]['snopjson']=json_decode($v['snopjson']);
                $data[$key]['snop'][$k]['return_status']=Order_return_goods::where('snopid', $v['snopid'])->value('status');
            }
//            dump($val->snop);
        }
        $address=$order->address_json;
        $address=json_decode($address);
//         dump($address);

//        dd($data);
        return view('mobile.pages.orderDetail',['order'=>$order,'data'=>$data[0],'address'=>$address]);
    }

    // 订单评价
    public function orderComment($id)
    {
        $uid=$_COOKIE['uid'];
        $order=Order::where(array('uid'=>$uid,'orderid'=>$id))->first();
        $orderList = Order::where('uid', $uid)->where('orderid', $id)->select('uniqueid', 'order_state', 'addressid', 'orderid', 'money', 'carriage',
            'evaluation_state', 'refund_state', 'return_status')->with('snop')->orderBy('created_at', 'desc')->get()->toArray();
        foreach ($orderList as $key => $val) {

            foreach($val['snop'] as $k=>$v){
                $orderList[$key]['snop'][$k]['snopjson']=json_decode($v['snopjson']);
                $orderList[$key]['snop'][$k]['return_status']=Order_return_goods::where('snopid', $v['snopid'])->value('status');
            }
//            dump($val->snop);
        }
//        dd($orderList[0]);
        return view('mobile.pages.orderComment',['orderList'=>$orderList[0],'order'=>$order]);
    }

    // 订单退款
    public function orderRefund($id)
    {
        //订单商品ID
        $snop=Order_commodity_snop::where('orderid',$id)->get();
        $snop_id='';
        foreach($snop as $key=>$val){
            $snop_id.=$val->snopid.',';
        }
        $snop_id=trim($snop_id,',');
        //物流公司

        $extend=Order_extend::where('orderid',$id)->first();
        if(!$extend){
            $extend=Order_extend::where('snopid',$id)->first();
        }
        $express=$extend->express;
        //物流单号
        $couriernumber=$extend->couriernumber;
        //订单总额
        $money = $_GET['get'];
        return view('mobile.pages.orderRefund',['snop_id'=>$snop_id,'express'=>$express,'couriernumber'=>$couriernumber,'id'=>$id,'money'=>$money]);
    }
    // 订单页面
    public function goodsOrder(Request $request)
    {
        $transaction=time().@$_COOKIE['uid'].rand(1111111, 9999999);
        //说明是分享进来的
        if (empty(@$_COOKIE['openid'])) {
            echo "<script>alert('首次购买,正在自动登录中...');</script>";
            header("Location:http://yqdz.xs.sunday.so/");
            die;
        }
        // dump($request->test);
        //这是第二个判断,在商品购买页的
        if($request->type==1){
//            //2.获取到网页授权的access_token
            $appid="wxaeae5b0ab20a1524";//这里的appid是假的演示用
            $appsecret="8f67908ced38352c197caa3e8cdc6392";//这里的appsecret是假的演示用
            $code=$_GET['code'];
            $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$code."&grant_type=authorization_code ";
//        dd($code);
                //3.拉取用户的openid
                $res = $this->request($url,'get');
                $res=json_decode($res);
                $access_token=$res->access_token;
                $openid=$res->openid;
                $url="https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
                $userInfo=$this->request($url,'get');
                $userInfo=json_decode($userInfo);
//        dd($userInfo);
                //添加用户到数据表
                $user=User::where('openid',$openid)->first();
//        dd($user);
                //如果没有查出数据,那么新增一条数据
                if(!$user){
//            dd(123465);
                $uid=User::insertGetId([
                    'openid'=>$openid,
                    'nickname'=>$userInfo->nickname,
                    'img'=>$userInfo->headimgurl,
                    'created_at'=>date('Y-m-d')
                ]);
//        dd('添加成功');
                setcookie("openid",$openid, time()+3600*24*365);
                setcookie("uid",$uid, time()+3600*24*365);
//            dd($_COOKIE['openid']);
                if(isset($_COOKIE['openid'])){
                    $userInfo=User::where('openid',$_COOKIE['openid'])->first();
//                dd($userInfo);
//                    return view('mobile.pages.user', ['userInfo' => $userInfo]);
                }
            }else{
                $userInfo=$user;
                setcookie("openid",$user->openid, time()+3600*24*365);
                setcookie("uid",$user->uid, time()+3600*24*365);
//                return view('mobile.pages.user', ['userInfo' => $userInfo]);
            }
        }
//        如果有上级ID的话,就查询有没有这个用户,如果没有添加这个用户,说明是分享进来的
        if ($request->upid && $request->type==0) {
//            dd($request->upid);
            $user = User::where('openid', @$_COOKIE['openid'])->count();
//             dd($user);
            if ($user == 0) {
                // dd('添加成功');
                include('wechat.php');
                $wechatObj->getAuthUrl(1,$request->id,$request->num,$request->upid,$request->status);
//                dd($a);
            }
        }
         //商品ID切割字符串,并去空
        $commodityid=explode(',',$request->id);
        foreach ($commodityid as $key => $val) {
            if(empty($val)){
                unset($commodityid[$key]);
            }
        }

        $num=explode(',',$request->num);
//        dd($num);
        foreach ($num as $key => $val) {
            if(empty($val)){
                unset($num[$key]);
            }
        }
//        dd($num);
        // dd($this->$uid);
        $openid = @$_COOKIE['openid'];
        $uid = @$_COOKIE['uid'];
//        return response()->json(["status" => 200, "msg" => $uid]);
            if($request->status==1){
                $totalPrice=0;
                //商品ID切割字符串,并去空
                $data = Order::where('orderid', $request->orderid)->select('transaction', 'order_state', 'addressid', 'orderid', 'money', 'carriage',
                    'evaluation_state', 'refund_state', 'return_status')->with('snop')->orderBy('created_at', 'desc')->get()->toArray();
                $transaction=$data[0]['transaction'];
                $cart=array();
                foreach ($data as $key => $val) {
                    foreach($val['snop'] as $k=>$v){
                        $data[$key]['snop'][$k]['snopjson']=json_decode($v['snopjson']);
                        if(Commodity::where('commodityid',$data[$key]['snop'][$k]['snopjson']->commodityid)->first()){
                            $data[$key]['goods'][$k]=Commodity::where('commodityid',$data[$key]['snop'][$k]['snopjson']->commodityid)->first()->toArray();
                        }else{
                            $data[$key]['goods'][$k]=array();
                        }
                        $data[$key]['goods'][$k]=Commodity::where('commodityid',$data[$key]['snop'][$k]['snopjson']->commodityid)->first()->toArray();
//                        $data[$key]['goods']=123456;
                        $data[$key]['goods'][$k]['thumbnail']= $data[$key]['snop'][$k]['snopjson']->thumbnail;

                        $cart[$k]['goods']=$data[$key]['goods'][$k];

                        $cart[$k]['money']=$data[$key]['snop'][$k]['snopjson']->money;

                        $cart[$k]['buy_num']=$data[$key]['snop'][$k]['nums'];

                        $totalPrice+=$cart[$k]['money']*$cart[$k]['buy_num'];

//                        $cart[$k]['moeny']=$data[$key]['snop'][$k]['snopjson']->money;
                    }
//                    dump($data);
//            dump($val->snop);
                }
                $address = Address::where(array('uid' => $uid, 'is_default' => 1))->first();
//                if(empty($address)){
//                    $address = Address::where(array('uid' => $uid, 'is_default' => 1))->first();
//                }
                //微信分享
                $options = [
                    // 前面的appid什么的也得保留哦
//            'debug'  => true,
                    /**
                     * 账号基本信息，请从微信公众平台/开放平台获取
                     */
                    'app_id' => 'wxaeae5b0ab20a1524',         // AppID
                    'secret' => '8f67908ced38352c197caa3e8cdc6392',     // AppSecret
                    'guzzle' => [
                        'timeout' => 3.0, // 超时时间（秒）
                        'verify' => false, // 关掉 SSL 认证（强烈不建议！！！）
                    ],
                ];
                $app = new Application($options);
//                dd($options);
                $js = $app->js;
//        dd($js);
//                dd($goods['money']);
//                dd($openid);
                $user = User::where('uid', $uid)->first();
                $config = $js->config(array('onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareAppMessage'));
                return view('mobile.pages.goodsOrder', ['cart' => $cart,'totalPrice'=>$totalPrice,'commodityid'=>$request->id,'num'=>$request->num, 'address' => $address, 'openid' => $openid, 'config' => $config, 'uid' => $uid, 'user' => $user, 'upid' => $request->upid,'status'=>$request->status,'orderid'=>$request->orderid,'transaction'=>$transaction]);
                die;
            }
//            dd($commodityid);
            $cart = Cart::whereIn('commodityid',$commodityid)->where('uid',$uid)->get()->toArray();
//                dd($cart);
        if(empty($cart)){
            $cart  = Commodity::where('commodityid',$commodityid)->get()->toArray();
        }
                //循环商品数据赋值
                $totalPrice=0;
//                dd($num);
                foreach ($cart as $key => $val) {
                    $cart[$key]['goods']=Commodity::where('commodityid',$val['commodityid'])->first()->toArray();

                    $cart[$key]['goods']['thumbnail']= json_decode($cart[$key]['goods']['thumbnail'], true);

                    $cart[$key]['goods']['thumbnail'] = $cart[$key]['goods']['thumbnail'][0];

                    $cart[$key]['buy_num']=$num[$key];

                    $totalPrice+=$num[$key]*$val['money'];

                }
            $address = Address::where(array('uid' => $uid, 'is_select' => 1))->first();
//            dd($address);
        if(empty($address)){
            $address = Address::where(array('uid' => $uid, 'is_default' => 1))->first();
        }
            //微信分享
            $options = [
                // 前面的appid什么的也得保留哦
//            'debug'  => true,
                /**
                 * 账号基本信息，请从微信公众平台/开放平台获取
                 */
                'app_id' => 'wxaeae5b0ab20a1524',         // AppID
                'secret' => '8f67908ced38352c197caa3e8cdc6392',     // AppSecret
                'guzzle' => [
                    'timeout' => 3.0, // 超时时间（秒）
                    'verify' => false, // 关掉 SSL 认证（强烈不建议！！！）
                ],
            ];
            $app = new Application($options);
//        dd($options);
            $js = $app->js;
//        dd($js);
            $user = User::where('uid', $uid)->first();
            $config = $js->config(array('onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareAppMessage'));
//        compact('config');
//        dd($config);
//        $address=(object)array();
                // dd($goods);

        //hwy
        $orderid = 0;
        //判断是否是 下单后更改的收获地址
        if(isset($_GET['orderid'])){
            $orderid = $_GET['orderid'];
        }
            return view('mobile.pages.goodsOrder', ['orderid'=>$orderid,'cart' => $cart, 'address' => $address, 'openid' => $openid, 'config' => $config, 'uid' => $uid, 'user' => $user, 'upid' => $request->upid,'status'=>$request->status,'transaction'=>$transaction,'test'=>$request->test,'totalPrice'=>$totalPrice,'commodityid'=>$request->id,'num'=>$request->num]);
        } 

    // 我的定制
    public function myFund()
    {
        $uid=$_COOKIE['uid'];
        $myFund=Design::where(array('uid'=>$uid,'is_qiye'=>1))->orderBy('designid','desc')->get();
        foreach ($myFund as $k => $v) {
            $v->img = $v->img[0];
        }

        return view('mobile.pages.myFund',['myFund'=>$myFund]);
    }

    // 我的奖励金
    public function myAward()
    {
        $uid=$_COOKIE['uid'];
        $myAward=Dividedinto::where(array('uid'=>$uid,'status'=>1))->sum('money');
        $content = Article::where('articleid',2)->value('content');
        return view('mobile.pages.myAward',['myAward'=>$myAward,'content'=>$content]);
    }

    // 提现
    public function awardWithdraw()
    {
        $uid=$_COOKIE['uid'];
        $myAward=Dividedinto::where(array('uid'=>$uid,'status'=>1))->sum('money');
        return view('mobile.pages.awardWithdraw',['myAward'=>$myAward]);
    }

    // 提现明细
    public function awardList()
    {
        $uid=$_COOKIE['uid'];
        $awardList=Dividedinto::where(array('uid'=>$uid,'status'=>1))->with('snop')->get();
        foreach ($awardList as $k => $v) {
            $a[$k] = $v['snop'];
            foreach ($a as $k1 => $v1) {
                $awardList[$k]['snop']['snopArray'] = json_decode($v1['snopjson']);
                // $awardList[$k]['snop']['return_status'] = Order_return_goods::where('snopid', $v1['snopid'])->value('status');

            }
        }
        // dump($awardList);
        return view('mobile.pages.awardList',['awardList'=>$awardList]);
    }

    // 我的关注
    public function attention()
    {
        $uid=$_COOKIE['uid'];
//        $uid=1;
        $attention=Love::where('uid',$uid)->join('commodity', 'commodity.commodityid', '=', 'love.commodityid')->get();
        foreach ($attention as $k => $v) {
            $attention[$k]['thumbnail'] = json_decode($v['thumbnail'], true);
            $attention[$k]['thumbnail'] = $attention[$k]['thumbnail'][0];
        }
        return view('mobile.pages.attention',['attention'=>$attention]);
    }

    // 我的消息列表
    public function message()
    {
        $uid=$_COOKIE['uid'];
        $message=User_msglist::where('user_msglist.uid',$uid)->join('user_msg', 'user_msglist.msgid', '=', 'user_msg.msg_id')->orderBy('user_msglist.is_view','asc')->get();
        return view('mobile.pages.message',['message'=>$message]);
    }

    // 我的消息详情
    public function messageDetail($id)
    {
        $uid=$_COOKIE['uid'];
        $msgDetail=User_msglist::where(array('user_msglist.uid'=>$uid,'user_msglist.msgid'=>$id))->join('user_msg', 'user_msglist.msgid', '=', 'user_msg.msg_id')->first();
        User_msglist::where(array('uid'=>$uid,'msgid'=>$id))->update(['is_view'=>1]);
        return view('mobile.pages.messageDetail',['msgDetail'=>$msgDetail]);
    }

    // 我的地址里恩表
    public function address(Request $request)
    {
        $orderid = 0;
        $uid=$_COOKIE['uid'];
        $id=$request->id;
        $num=$request->num;
        $money=$request->money;
        $address=Address::where(array('uid'=>$uid))->get();

        // hwy
        if(isset($_GET['orderid'])){
            $orderid = $_GET['orderid'];
        }
        $type = 0;
        if(isset($_GET['type'])){
            $type = 1;
        }
        // dd($address,$id,$num,$money);
        return view('mobile.pages.address',['type'=>$type,'address'=>$address,'id'=>$id,'num'=>$num,'money'=>$money,'orderid'=>$orderid]);
    }

    // 新增地址
    public function addressNew(Request $request)
    {
        $orderid = 0;
        if(isset($_GET['orderid'])){
            $orderid = $_GET['orderid'];
        }
        if($_GET['type'] == 1){
            $type = 1;
        }else{
            $type = 0;
        }
        return view('mobile.pages.addressNew',['type'=>$type,'orderid'=>$orderid,'id'=>$request->id,'num'=>$request->num,'money'=>$request->money]);
    }

    // 修改地址
    public function addressEdit(Request $request)
    {
        // dd(123456);
        // dd($request);
        $uid=$_COOKIE['uid'];
        $orderid = 0;
        // dump($addressid);
        if ($request->addressid) {
            $address=Address::where(array('uid'=>$uid,'addressid'=>$request->addressid))->first();

        }
        if(isset($_GET['orderid'])){
            $orderid = $_GET['orderid'];
        }
        if($_GET['type'] == 1){
            $type = 1;
        }else{
            $type = 0;
        }
        return view('mobile.pages.addressEdit',['type'=>$type,'orderid'=>$orderid,'id'=>$request->id,'num'=>$request->num,'money'=>$request->money,'address'=>$address]);
    }

    // 平台说明
    public function explain()
    {
        $explain=Article::where('articleid',1)->first();

        return view('mobile.pages.explain',['explain'=>$explain]);
    }
    // 购物车
    public function cart()
    {
        $uid=@$_COOKIE['uid'];
        if (empty(@$uid)) {
            echo "<script>alert('首次购买,正在自动登录中...');</script>";
            header("Location:http://yqdz.xs.sunday.so/");
            die;
        }
        //查询该用户的购物车信息
        $cartList=Cart::where('uid',$uid)->get()->toArray();
        $totalPrice=0;
        //遍历赋值
        foreach ($cartList as $key => $val) {

            $goods=Commodity::where('commodityid',$val['commodityid'])->first()->toArray();
            if(!empty($goods)){
                $goods['thumbnail'] = json_decode($goods['thumbnail'], true);
                $goods['thumbnail'] = $goods['thumbnail'][0];
                $cartList[$key]['goods']=$goods;
                $totalPrice+=$val['money']*$val['nums'];
            }
        }


//        dd($cartList);
        return view('mobile.pages.cart',['cartList'=>$cartList,'totalPrice'=>$totalPrice]);
    }

    /**
     * @param Request $request
     * @return mixed
     * author hongwenyang
     * method description :已发货产品列表
     */

    public function wuliuList(Request $request){
        $snopid = $request->id;
//        $orderId = Order_commodity_snop::where('snopid',$snopid)->value('orderid');
        $orderlist = Order_commodity_snop::where('orderid',$snopid)->where('is_extend',1)->select('snopjson','snopid')->get();

        foreach($orderlist as $k=>$v){
            $v->snopjson = json_decode($v->snopjson);
        }
        return view('mobile.pages.orderWuliulist',compact('orderlist'));
    }


    /**
     * @param Request $request
     * @return mixed
     * author hongwenyang
     * method description : 物流查询
     */

    public function wuliu(Request $request){
        $orderId = Order_commodity_snop::where('snopid',$request->id)->value('orderid');

//        $order_goods=Order::where('order.orderid',$orderId)
//            ->join('order_commodity_snop','order.orderid','=','order_commodity_snop.orderid')
//            ->join('order_extend','order.orderid','=','order_extend.orderid')->first();
        $order_goods=Order_extend::where('snopid',$request->id)->first();

        $express=$order_goods->express;
        if($express){
            if($express=='申通快递'){
                $com='sto';
            }elseif ($express=='EMS'){
                $com='ems';
            }elseif ($express=='顺丰快递'){
                $com='sf';
            }elseif ($express=='圆通快递'){
                $com='yt';
            }elseif ($express=='中通'){
                $com='zto';
            }elseif ($express=='天天快递'){
                $com='tt';
            }elseif ($express=='韵达快递'){
                $com='yd';
            }elseif ($express=='快捷快递'){
                $com='kj';
            }elseif ($express=='百世快递'){
                $com='ht';
            }else if($express == "邮政快递包裹"){
                $com='youzhengguonei';
            }
//        com=sto&no=3342715889953&dtype=&key=e583d24c4a86f1ce45573adc97e95f46
            $url='http://v.juhe.cn/exp/index?key=e583d24c4a86f1ce45573adc97e95f46&com='.$com.'&no='.$order_goods->couriernumber;
//        dd($url);
            $res=$this->request($url,'get');
            $res=json_decode($res,true);
//        dd($res['result']['list']);
            $wuliu=$res['result']['list'];
            if(empty($wuliu)){
                $wuliu=array();
            }
        }
//            dd($wuliu);


//        dd($express);
        return view('mobile.pages.orderWuliu',['order_goods'=>$order_goods,'wuliu'=>$wuliu]);
    }

    /**
     * @param Request $request
     * @return string
     * author hongwenyang
     * method description : 发起定制图片处理
     */
    public function  SaveToFile(Request $request){
        $imgData = $_REQUEST['images'];
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $imgData, $result)){
            $type = $result[2];
            $rand = md5(rand(1000,9999).time());
            $new_file = './uploads/'.$rand.'.'.$type;
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $imgData)))){
                return '/uploads/'.$rand.'.'.$type;
            }

        }
    }
}
