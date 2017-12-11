<?php

namespace App\Http\Controllers\admin;


use App\Models\Address;
use App\Models\Dividedinto;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function lists(Request $request){

        if($request->type == "1"){
            $list=User::where('nickname','like','%'.$request->keyword.'%')->paginate(10);
        }elseif ($request->type =="2"){
            $list=User::where('uid',$request->keyword)->paginate(10);
        }else{
            $list=User::paginate(10);
        }
        return view('admin.user.lists',['list'=>$list]);
    }

    public function del(Request $request){
        $id=$request->id;
        $type=$request->type;
        if ($type == 1){
            $data=User::where('uid',$id)->update([
                'is_delete'=> 2,
            ]);
        }elseif($type == 2){
            $data=User::where('uid',$id)->update([
                'is_delete'=> 1,
            ]);
        }
        if($data){
            return response()->json([
                'status' => '200',
            ]);
        }else{
            return response()->json([
                'status' => '404',
            ]);
        }
    }

    public function fenxiao(Request $request){
        //查询当前用户的1,2,3级推荐人
        $user=User::where('uid',$request->id)->select('first','second','three')->first();

        //查询上级一级推荐人
        $first=User::where('uid',$user['first'])->first();

        //查询上级二级推荐人
        $second=User::where('uid',$user['second'])->first();

        //查询上级三级推荐人
        $three=User::where('uid',$user['three'])->first();

        //查询下级一级推荐人
        $firstList=User::where('first',$request->id)->get();

        //查询下级二级推荐人
        $secondList=User::where('second',$request->id)->get();

        //查询下级三级推荐人
        $threeList=User::where('three',$request->id)->get();

        return view('admin.user.fenxiao',['first'=>$first,'second'=>$second,'three'=>$three,'firstList'=>$firstList,'secondList'=>$secondList,'threeList'=>$threeList]);
    }
    //查找用户的地址等信息
    public function info(Request $request){
        //查找用户信息
        $user=User::where('uid',$request->id)->first()->toArray();
//        dd($user);
        //根据用户ID查找地址信息
        $addressList=Address::where('uid',$request->id)->get()->toArray();
//        dd($addressList);
        return view('admin.user.info',['user'=>$user,'addressList'=>$addressList]);
    }

    private function money_add($array){
        $item=array();
        foreach($array as $k=>$v){
            if(!isset($item[$v['fromuid']])){
                $item[$v['fromuid']] = $v;
            }else{
                $item[$v['fromuid']]['money']+=$v['money'];
            }
        }
        return array_values($item);
    }

    /**
     * @param Request $request
     * author hongwenyang
     * method description : 用户数据导出  时间紧迫用表格速度快点  后期有时间改phpexcel
     */
    public function export(Request $request){
        $search = $request->except('s');
        if(!$search['keyword']){
            //查询所有数据
            $data = User::get();
            foreach($data as $k=>$v){
                $v->address = Address::where('uid',$v->uid)->get();
            }
        }else{
            if($search['type'] == 1){
                // 搜昵称

                $title = 'nickname';
            }else{
                // 搜id

                $title = 'id';
            }

            $data = User::where($title,'like',"%".$search['keyword']."%")->get();
            foreach($data as $k=>$v){
                $v->address = Address::where('uid',$v->uid)->get();
            }
        }
        $outData = [];
        foreach($data as $k=>$v){
            $outData[$k]['nickname'] = $v->nickname;
            $outData[$k]['created_at'] = $v->created_at;
            $outData[$k]['isbuy'] = $v->isbuy == 0 ? "未购买" : "已购买";
            if(!empty($v->address)){
                foreach($v->address as $k1=>$v1){
                    $outData[$k]['address'][$k1]['name'] = $v1->name;
                    $outData[$k]['address'][$k1]['phone'] = $v1->phone;
                    $outData[$k]['address'][$k1]['address'] = $v1->province.$v1->city.$v1->district.$v1->address;
                    $outData[$k]['address'][$k1]['is_default'] = $v1->is_default == 0 ? "否" : "是";
                }
            }
        }
        $strTable ='<table width="100%" border="1">';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">微信昵称</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">注册时间</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">是否购买</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">收货人姓名</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">手机号</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">地址</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">默认地址</td>';
        $strTable .= '</tr>';
        foreach($outData as $k=>$val){

            $strTable .= '<tr>';
            if(!empty($val['address'])){
                foreach($val['address'] as $v1) {
                    $strTable .= '<tr>';
                    $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="140">'.$val['nickname'].'</td>';
                    $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="140">'.$val['created_at'].'</td>';
                    $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="140">'.$val['isbuy'].'</td>';
                    $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="140">' . $v1['name'] . '</td>';
                    $strTable .= '<td style="text-align:center;font-size:14px;mso-number-format:\'\@\'" height="30" width="140">' . $v1['phone'] . '</td>';
                    $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="300">' . $v1['address'] . '</td>';
                    $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="140">' . $v1['is_default'] . '</td>';
                    $strTable .= '</tr>';
                }
            }else{
                $strTable .= '<tr>';
                $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="140">'.$val['nickname'].'</td>';
                $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="140">'.$val['created_at'].'</td>';
                $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="140">'.$val['isbuy'].'</td>';
                $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="140"></td>';
                $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="140"></td>';
                $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="300"></td>';
                $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="140"></td>';
                $strTable .= '</tr>';
            }

            $strTable .= '</tr>';
        }
        $strTable .='</table>';
        header("Content-type: application/vnd.ms-excel");
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=用户列表.xls");
        header('Expires:0');
        header('Pragma:public');
        echo $strTable;
        exit();
    }
}
