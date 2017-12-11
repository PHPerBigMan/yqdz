<?php
namespace App\Http\Controllers\FrontEnd\wechat;
define("TOKEN", "yiqidingzhi");
$wechatObj = new wechat('wxaeae5b0ab20a1524','8f67908ced38352c197caa3e8cdc6392');

class wechat
{
    protected $appId;
    protected $appSecret;
    public function __construct($appid,$appsecret) {
        $this->appId = $appid;
        $this->appSecret = $appsecret;
    }

    // 设置白名单 ip 阿里云是设置内网ip
    // 发送文字消息 主动发送 客服消息
    public function send_service_text($openid,$text){
        $json = '{
            "touser":"'.$openid.'",
            "msgtype":"text",
            "text":
            {
                 "content":"'.$text.'"
            }
        }';
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$this->getAccessToken();
        $rs = $this->request($url,"post",$json);
        return $rs;
    }
    ###############################################################################################
    # 获取获得code 的重定向url
    # $scope = snsapi_base / snsapi_userinfo
    ###############################################################################################
    public function getAuthUrl($type=0,$id,$num,$upid,$status,$scope='snsapi_userinfo'){
        if($type==1){
            $back_url = 'http://yqdz.xs.sunday.so/goods-order?type='.$type.'&id='.$id.'&num='.$num.'&upid='.$upid.'&status='.$status;
        }else if($type==2){
            //echo "123";die;  
            $back_url = 'http://yqdz.xs.sunday.so/goods/'.$id.'/'.$type;
        }else if($type == 3){
            $back_url = 'http://yqdz.xs.sunday.so/getUserOpenId?id='.$id;
        }else{
            $back_url = 'http://yqdz.xs.sunday.so/getUserOpenId';
        }
        $redirect_uri = urlencode($back_url);
        $state = 'wechat';
        $scope = 'snsapi_userinfo';
        //$scope = 'snsapi_base';
        //$scope = 'snsapi_login';
        //$oauth_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $cfg_appid . '&redirect_uri=' . $redirect_uri . '&response_type=code&scope=' . $scope . '&state=' . $state . '#wechat_redirect';
        //$oauth_url="https://open.weixin.qq.com/connect/qrconnect?appid=".$cfg_appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=".$scope."&state=".$state."#wechat_redirect";
        //$oauth_url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect ";
        $oauth_url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appId."&redirect_uri=".$redirect_uri."&response_type=code&scope=".$scope."&state=".$state."#wechat_redirect";
      //dd($oauth_url);
//                return $oauth_url;
//        redirect($oauth_url);
//        dd(132456);
        header("Location:$oauth_url");
        exit;
    }


    ###############################################################################################
    # 通过 mediaid 获取图片
    ###############################################################################################
    public function getmedia($media_id){
        $url = "https://api.weixin.qq.com/cgi-bin/media/get";
        $url .= "?access_token=".$this->getAccessToken();
        $url .= "&media_id=". $media_id;
        return $url;
    }




    ###############################################################################################
    # 通过 获取菜单
    ###############################################################################################
    public function getmenu(){
        $url = "https://api.weixin.qq.com/cgi-bin/menu/get";
        $url .= "?access_token=".$this->getAccessToken();
        $res = $this->request($url);
        return $res;
    }

    ###############################################################################################
    # 设置菜单
    ###############################################################################################
    public function setmenu($data){
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create";
        $url .= "?access_token=".$this->getAccessToken();
        $res = $this->request($url,'post',$data);
        return $res;
    }




    ###############################################################################################
    # 通过code 获取 微信会员数据
    ###############################################################################################
    public function getuserinfo($openid,$token){
        $url = "https://api.weixin.qq.com/sns/userinfo";
        $url .= "?access_token=".$token;
        $url .= "&openid=". $openid;
        $url .= "&lang=zh_CN";
        $temp = $this->request($url);
        return json_decode($temp,true);
    }


    ###############################################################################################
    # 通过code 获取 所有数据
    ###############################################################################################
    public function getdata($code){
        $url = '';
        $url .= 'https://api.weixin.qq.com/sns/oauth2/access_token';
        $url .= '?appid='.$this->appId;
        $url .= '&secret='.$this->appSecret;
        $url .= '&code='.$code;
        $url .= '&grant_type=authorization_code';
        $result = $this->request($url);
        $error = $this->errorCode($result);
        if(!$error){
            $arr = json_decode($result,true);
            return $arr;
        }else{
            return $error;
        }
    }



    ###############################################################################################
    # 通过code 获取 openid
    ###############################################################################################
    public function getOpenid($code,$field='openid'){
        $url = '';
        $url .= 'https://api.weixin.qq.com/sns/oauth2/access_token';
        $url .= '?appid='.$this->appId;
        $url .= '&secret='.$this->appSecret;
        $url .= '&code='.$code;
        $url .= '&grant_type=authorization_code';
        $result = $this->request($url);
        $error = $this->errorCode($result);
        if(!$error){
            $arr = json_decode($result,true);
            return $arr[$field];
        }else{
            return $error;
        }
    }
    ###############################################################################################
    # 处理 getSignPackage start
    ###############################################################################################
    public function getSignPackage($url='') {
        $jsapiTicket = $this->getJsApiTicket();
        // $url 传递进来，必须和当前分享页一致
        if($url == ''){
            // 注意 URL 一定要动态获取，不能 hardcode.
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }
        $timestamp = time();
        $nonceStr = $this->createNonceStr();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        $signPackage = array(
            "appId"     => $this->appId,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }
    ###############################################################################################
    # 处理 getSignPackage start
    ###############################################################################################
    ###############################################################################################
    # 处理 jszpi_ticket start
    ###############################################################################################
    // 获取 jsapi_ticket
    public function getJsApiTicket() {
        $path = $this->pathJsapiTicket();
        if(file_exists($path)){
            $data = json_decode(file_get_contents($path),true);
            if($data['expire_time'] < time()) {
                $ticket = $this->setJsApiTicket();
            }else{
                $ticket = $data['ticket'];
            }
        }else{
            $ticket = $this->setJsApiTicket();
        }
        return $ticket;
    }
    //创建新的 JsApi ticket
    protected function setJsApiTicket(){
        $accessToken = $this->getAccessToken();
        // 如果是企业号用以下 URL 获取 ticket
        // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
        $res = json_decode($this->request($url),true);
        if (array_key_exists('ticket',$res)) {
            $ticket = $res['ticket'];
            // 保存的$data;
            $data = array();
            $data['expire_time'] = time() + 7100;
            $data['ticket'] = $ticket;
            $path = $this->pathJsapiTicket();
            $fp = fopen($path,"w");
            fwrite($fp, json_encode($data));
            fclose($fp);
            return $ticket;
        }else{
            return false;
        }
    }
    // 保存 jszpi_ticket 文件路径
    protected function pathJsapiTicket(){
        $path = dirname(__FILE__);
        $path = str_replace('\\','/',$path);
        $path = $path . '/'.$this->appId.'_ticket.json';
        return $path;
    }
    ###############################################################################################
    # 处理 jszpi_ticket end
    ###############################################################################################
    ###############################################################################################
    # 处理 token start
    ###############################################################################################
    // 获取token
    public function getAccessToken() {
        $path = $this->pathAccessToken();
        if(file_exists($path)){
            $data = json_decode(file_get_contents($path),true);
            if($data['expire_time'] < time()) {
                $access_token = $this->setAccessToken();
            }else{
                $access_token = $data['access_token'];
            }
        }else{
            $access_token = $this->setAccessToken();
        }
        return $access_token;
    }
    //创建新的 token
    protected function setAccessToken(){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
        $res = json_decode($this->request($url),true);
        $access_token = $res['access_token'];
        if ($access_token) {
            // 保存的$data;
            $data = array();
            $data['expire_time'] = time() + 7100;
            $data['access_token'] = $access_token;
            $path = $this->pathAccessToken();
            $fp = fopen($path,"w");
            fwrite($fp, json_encode($data));
            fclose($fp);
            return $access_token;
        }else{
            return false;
        }
    }
    // 保存token文件路径
    protected function pathAccessToken(){
        $path = dirname(__FILE__);
        $path = str_replace('\\','/',$path);
        $path = $path . '/'.$this->appId.'_token.json';
        return $path;
    }
    ###############################################################################################
    # 处理 token end
    ###############################################################################################
    // 错误编码
    protected function errorCode($json){
        $arr = json_decode($json,true);
        // 没有错误 返回 false
        if(!array_key_exists('errcode',$arr)){
            return false;
        }
        $tmp = [
            'code' => $arr['errcode'],
            'msg' => $arr['errmsg'],
            'summary' => ''
        ];
        switch($arr['errcode']){
            case '40029':
                $tmp['summary'] = 'code 无效';
                break;
        }
        return $tmp;
    }
    // 随机字符
    protected function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
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
}