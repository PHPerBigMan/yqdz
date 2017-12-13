<?php

namespace App\Http\Middleware;

use App\Models\AdminGroup;
use App\Models\AdminRule;
use Closure;
use Illuminate\Support\Facades\Request;
class UserPermession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // 获取当前操作的url
        $action = $request->input('s');

        $other_action = array('handle');
        $res = session('admin');

        $map['groupid'] = $res->groupid;
        $rule = AdminGroup::where($map)->first();
        $temp_arr = $rule->ruleid;

        $new['identifying'] = $action;
        $rule_id = AdminRule::where($new)->value('ruleid');

        if($res->groupid != "2"){
//            dd($action);
            if(is_null($rule_id) || !in_array($rule_id,$temp_arr)){
                if(!in_array($action,$other_action)){
                    $return404 = ['/admin/user/lists','/admin/commodity/lists','/admin/classify/lists'
                    ,'/admin/commoditycomment/lists','/admin/commodity/lists','/admin/order/lists',
                        '/admin/orderRefunds/lists','/admin/orderReturnGoods/lists','/admin/admin/list',
                        '/admin/adminGroup/list','/admin/article/lists','/admin/carriage/lists',
                        '/admin/dividedinto/lists','/admin/dividedinto/lists2','/admin/order/detail/','/admin/config/edit',
                        '/admin/order/export','/admin/carriage/express'];

                    if(in_array($action,$return404)){
//                      return response('对不起您没有查看的权限',401);
                        return redirect('admin/error');
                  }else{
                      return response()->json(['status' => '404', 'msg' =>'您没有权限',]);
                  }
                }
            }
        }

        return $next($request);
    }
}
