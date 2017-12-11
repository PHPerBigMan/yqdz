<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="/assets/css/amazeui.min.css"/>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <script src="//cdn.bootcss.com/vue/1.0.24/vue.min.js"></script>
    <style>
        .am-btn-default{background: none}
        .am-dropdown-content{z-index: 9999;background: #fff}
        label{font-weight: normal;margin-right: 10px;}
        .am-form-group{margin-bottom: 0px;display: inline-block;margin-right: 10px;}
        .am-panel{margin-right: 30px;margin-left: 15px;border: none;box-shadow: none;-webkit-box-shadow: none}
        .am-panel-bd{padding: .5rem;}
        .am-form select{width: 100px;display: inline-block;margin-right: 10px;}
        .am-form input[type='text']{width: 200px;display: inline-block}
        .am-form input[type=number], .am-form input[type=search], .am-form input[type=text], .am-form input[type=password], .am-form input[type=datetime], .am-form input[type=datetime-local], .am-form input[type=date], .am-form input[type=month], .am-form input[type=time], .am-form input[type=week], .am-form input[type=email], .am-form input[type=url], .am-form input[type=tel], .am-form input[type=color], .am-form select, .am-form textarea, .am-form-field{padding: .4rem;border-radius: 0px;}
        .laydate-icon, .laydate-icon-default, .laydate-icon-danlan, .laydate-icon-dahong, .laydate-icon-molv{height: 32px !important;padding-left: 10px!important;}
    </style>

</head>
<body>
<div class="am-cf admin-main2">
    <!-- content start -->
    <div class="admin-content">
        <div class="admin-content-body">
            <div class="am-cf am-padding am-padding-bottom-0 hw-nav">
                <div class="am-fl am-cf">
                    <ol class="am-breadcrumb">
                        <li class="am-active">每日销售统计</li>
                    </ol>
                </div>
                <div class="am-fr am-cr">
                    <a class="am-btn am-btn-warning hw-shuaxin" href="javascript:location.replace(location.href);">
                        <i class="am-icon-refresh"></i>
                    </a>
                </div>
            </div>

            <div class="am-panel am-panel-default">
                <form class="am-form" action="/admin/baobiao/xiaoshou" method="get" id="from1">
                    <div class="am-panel-bd">
                        <label for="doc-select-1">开始日期：</label>
                        <div class="am-form-group am-form-icon">
                            <input id="start" class="laydate-icon" value="{{$start}}" readonly="" name="start">
                        </div>
                        <label for="doc-select-1"> 结束日期：</label>
                        <div class="am-form-group am-form-icon">
                            <input id="end" class="laydate-icon" value="{{$end}}" readonly="" name="end">
                        </div>
                        <a class="am-btn am-btn-sm am-btn-primary" @click="submit">
                        <i class="am-icon-search"></i>
                        筛选
                        </a>
                    </div>
                </form>
            </div>

            <div class="am-g">
                <div class="am-u-sm-12">
                    <div id="container" style="height: 400px;padding: 20px;"></div>
                </div>

            </div>
        </div>
    </div>
    <!-- content end -->
</div>

<script src="//cdn.bootcss.com/jquery/2.0.2/jquery.min.js"></script>
<script src="/layer/layer.js"></script>
<script src="/layer/laydate.js"></script>
<script src="//cdn.bootcss.com/echarts/3.0.0/echarts.min.js"></script>
<script>
    var start = {
        elem: '#start',
        format: 'YYYY-MM-DD',
        max: '2099-06-16 23:59:59', //最大日期
        istime: true,
        istoday: true,
        choose: function(datas){
            end.min = datas;
        }
    };
    var end = {
        elem: '#end',
        format: 'YYYY-MM-DD',
        max: '2099-06-16 23:59:59',
        istime: true,
        istoday: false,
        choose: function(datas){
            start.max = datas; //结束日选好后，重置开始日的最大日期
        }
    };
    laydate(start);
    laydate(end);
</script>
<script type="text/javascript">
    var vm = new Vue({
        el: 'html',
        data: {
            message: 'Hello Vue.js!'
        },
        methods: {
            submit: function (event) {
                $("#from1").submit()
            }
        }
    })
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('container'));

    // 指定图表的配置项和数据
    var option = {
        title:{
            show:true,
            text: '每日销售统计',
            left: 'left',
            textStyle:{
                color:'#008acd',
                fontStyle:'normal',
                fontSize:13
            }
        },
        tooltip : {
            trigger: 'axis',
            axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
            }
        },
        toolbox: {
            feature: {
                dataZoom: {
                    yAxisIndex: 'none'
                },
                restore: {},
                saveAsImage: {}
            }
        },
        legend: {
            data:['销量(件)','销售额','退款金额']
        },
        grid: {
            top: '150px',
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis : [
            {
                type : 'category',
                data : {!! $alldate !!},
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : [
            {
                name:'销量(件)',
                type:'bar',
                label: {
                    normal: {
                        show: true,
                        position: 'top'
                    }
                },
                smooth: true,
                data:{!! $nub !!},
            },
            {
                name:'销售额',
                type:'bar',
                label: {
                    normal: {
                        show: true,
                        position: 'top'
                    }
                },
                smooth: true,
                data:{!! $money !!},
            },
            {
                name:'退款金额',
                type:'bar',
                label: {
                    normal: {
                        show: true,
                        position: 'top'
                    }
                },
                smooth: true,
                data:{!! $return !!},
            },
        ]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
</script>
</body>
</html>