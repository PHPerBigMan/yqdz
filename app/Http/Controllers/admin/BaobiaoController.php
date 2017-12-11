<?php

namespace App\Http\Controllers\admin;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BaobiaoController extends Controller
{
    public function user()
    {
        $end = date("Y-m-d", strtotime('+1 days'));//默认结束时间为当天
        $start = date('Y-m-d', strtotime('-15 days'));//默认开始时间为7天前
//        $data = M()->query("SELECT nickname,regtime FROM user Where DATE_FORMAT(regtime,'%Y-%m-%d') >= '$start' and DATE_FORMAT(regtime,'%Y-%m-%d')<= '$end'");
        $data = DB::select("SELECT nickname,created_at FROM user Where DATE_FORMAT(created_at,'%Y-%m-%d') >= '$start' and DATE_FORMAT(created_at,'%Y-%m-%d')<= '$end'");
        $nub = array_column($data, 'nub');

        $days = (strtotime($end) - strtotime($start)) / 3600 / 24; //获取某一时间段内所有日期
        $start_day = $start;
        $arr = array();
        for ($i = 0; $i < $days; $i++) {
            $arr[] = date('Y-m-d', strtotime($start_day) + $i * 24 * 60 * 60);
        }

        $all_date = $arr;

        foreach ($all_date as $k => $v) {
            $nub[$k] = 0;
            foreach ($data as $k1 => $v1) {
                if (date("Y-m-d", strtotime($v1->created_at)) == $v) {
                    $nub[$k]++;
                }
            }
        }

        return view('admin.baobiao.user', ['alldate' => json_encode($all_date), 'nub' => json_encode($nub)]);
    }

    //打款统计
    public function money(Request $request)
    {
        if (!empty($request->start || !empty($request->end))) {
            $start = $request->start;
            $end = $request->end;
        } else {
            $end = date("Y-m-d", strtotime('+1 days'));//默认结束时间为当天
            $start = date('Y-m-d', strtotime('-15 days'));//默认开始时间为7天前
        }
        $data = DB::select("SELECT money,created_at FROM dividedinto Where DATE_FORMAT(created_at,'%Y-%m-%d') >= '$start' and DATE_FORMAT(created_at,'%Y-%m-%d')<= '$end' and status=2");
//        dd($data);
        $days = (strtotime($end) - strtotime($start)) / 3600 / 24; //获取某一时间段内所有日期
        $start_day = $start;
        $arr = array();
        for ($i = 0; $i < $days; $i++) {
            $arr[] = date('Y-m-d', strtotime($start_day) + $i * 24 * 60 * 60);
        }

        $all_date = $arr;
        foreach ($all_date as $k => $v) {
            $money[$k] = 0;
            foreach ($data as $k1 => $v1) {
//                dd($v1);
                if (date("Y-m-d", strtotime($v1->created_at)) == $v) {
                    $money[$k] = $money[$k] + $v1->money;
                }
            }
        }
//        dd($money);
        return view('admin.baobiao.money', ['alldate' => json_encode($all_date), 'nub' => json_encode($money), 'start' => $start, 'end' => $end]);
    }

    //计算销售量，销售额
    public function xiaoshou(Request $request)
    {

        if (!empty($request->start || !empty($request->end))) {
            $start = $request->start;
            $end = $request->end;
        } else {
            $end = date("Y-m-d", strtotime('+1 days'));//默认结束时间为当天
            $start = date('Y-m-d', strtotime('-15 days'));//默认开始时间为7天前
        }
        $days = (strtotime($end) - strtotime($start)) / 3600 / 24; //获取某一时间段内所有日期
        $start_day = $start;
        $arr = array();
        for ($i = 0; $i < $days; $i++) {
            $arr[] = date('Y-m-d', strtotime($start_day) + $i * 24 * 60 * 60);
        }

        $all_date = $arr;

        $data = Order::where('pay_time', '>', $start)->where('pay_time', '<', $end)->with('snop')->select('pay_time', 'orderid', 'money', 'refund_amount')->limit(10,12)->get();
//        dd($data);

        foreach ($data as $k => $v) {
            $data[$k]['nub'] = 0;

            foreach ($data[$k]['snop'] as $k1 => $v1) {
                $data[$k]['nub'] = $data[$k]['snop'][0]['nums'];
//                dd($data[$k]['nub']);
            }
            unset($data[$k]['snop']);
        }

        foreach ($all_date as $k => $v) {
//            dd($v);
            $nub[$k] = 0;
            $money[$k] = 0;
            $return[$k] = 0;
//            dd($data);
            foreach ($data as $k1 => $v1) {
//                dd($v1);
                if (date("Y-m-d", strtotime($v1['pay_time'])) == $v) {
                    $nub[$k] = $nub[$k] + $v1['nub'];
                    $money[$k] = $money[$k] + $v1['money'];
//                    dd($money);
                    $return[$k] = $return[$k] + $v1['refund_amount'];
                }
            }
        }
//        dd($money);
        return view('admin.baobiao.xiaoshou', ['alldate' => json_encode($all_date), 'nub' => json_encode($nub), 'start' => $start, 'end' => $end, 'money' => json_encode($money), 'return' => json_encode($return)]);
    }
}
