<?php

namespace App\Console;

use App\Models\Statistics;
use App\Models\Order;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use function strtotime;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call(function(){
            $data=Statistics::where('created_at',date("Y-m-d"))->first();
            if(empty($data)) {
                Statistics::create([
                    'order_nub' => 0,
                    'order_money' => 0,
                    'browse_nub' => 0,
                    'visitor_nub' => 0,
                    'created_at' => date("Y-m-d")
                ]);
            }
        })->daily();
        $schedule->call(function(){
            //发货后15天内 自动确认待收货的订单
            $order=Order::join('order_extend as e','order.orderid','=','e.orderid')->where('order_state',30)->select('order.*','e.created_at as extend_time')->limit(500)->get();
//            $id=DB::table('label')->insert([
//                'name'=>'测试'
//            ]);
            foreach ($order as $key => $val) {
                if(strtotime($val->extend_time)+86400*15<=time()){
                    Order::where('orderid',$val->orderid)->update(['order_state'=>50,'endtime'=>date("Y-m-d H:i:s")]);
                }
            }
        })->dailyAt('1:00');


        $schedule->call(function(){
            //发货后15天内 自动确认待收货的订单
            $order=Order::where('order_state',10)->limit(500)->get();
//            $id=DB::table('label')->insert([
//                'name'=>'测试'
//            ]);
            foreach ($order as $key => $val) {
                if(strtotime($val->created_at)+86400<=time()){
                    //修改订单状态
                    Order::where('orderid',$val->orderid)->update(['order_state'=>0]);
                    //修改对应商品库存

                }
            }
        })->dailyAt('1:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
