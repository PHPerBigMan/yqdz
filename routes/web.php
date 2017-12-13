<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('admin/index/login', function () {
    return view('admin.index.login');
});
Route::post('admin/index/postlogin', 'admin\IndexController@login');
Route::get('admin/index/logout', 'admin\IndexController@logout');

Route::group(['middleware' => ['adminlogin']], function () {
    Route::get('admin/index/center', function () {
        return view('admin.index.center');
    });

});
Route::get('admin/index/welcome', 'admin\IndexController@welcome')->middleware('adminlogin');
Route::get('admin/admin/list', 'admin\AdminController@lists')->middleware(['adminlogin','userpermisson']);
Route::get('admin/admin/add', 'admin\AdminController@add')->middleware(['adminlogin','userpermisson']);
Route::get('admin/admin/edit', 'admin\AdminController@edit')->middleware(['adminlogin','userpermisson']);
Route::post('admin/admin/handle', 'admin\AdminController@handle');
Route::get('admin/admin/del', 'admin\AdminController@del')->middleware('userpermisson');;

Route::get('admin/adminGroup/list', 'admin\AdminGroupController@lists')->middleware(['adminlogin','userpermisson']);
Route::get('admin/adminGroup/add', 'admin\AdminGroupController@add')->middleware(['adminlogin']);
Route::get('admin/adminGroup/edit', 'admin\AdminGroupController@edit')->middleware('adminlogin');
Route::post('admin/adminGroup/handle', 'admin\AdminGroupController@handle')->middleware('adminlogin');
Route::get('admin/adminGroup/del', 'admin\AdminGroupController@del')->middleware('userpermisson');

Route::get('admin/user/lists', 'admin\UserController@lists')->middleware(['adminlogin','userpermisson']);
Route::get('admin/user/del', 'admin\UserController@del')->middleware('userpermisson');
Route::get('admin/user/fenxiao', 'admin\UserController@fenxiao')->middleware('adminlogin');
Route::get('admin/user/info', 'admin\UserController@info')->middleware('adminlogin');
Route::get('admin/user/export', 'admin\UserController@export')->middleware('adminlogin');

Route::get('admin/carriage/lists', 'admin\CarriageController@lists')->middleware(['adminlogin','userpermisson']);
Route::get('admin/carriage/express', 'admin\CarriageController@express')->middleware(['adminlogin','userpermisson']);
Route::post('admin/carriage/express_edit', 'admin\CarriageController@express_edit')->middleware(['adminlogin','userpermisson']);
Route::post('admin/carriage/express_add', 'admin\CarriageController@express_add')->middleware(['adminlogin','userpermisson']);
Route::get('admin/carriage/express_del', 'admin\CarriageController@express_del')->middleware(['adminlogin','userpermisson']);
Route::get('admin/carriage/add', 'admin\CarriageController@add')->middleware(['adminlogin','userpermisson']);
Route::get('admin/carriage/edit', 'admin\CarriageController@edit')->middleware(['adminlogin','userpermisson']);
Route::post('admin/carriage/handle', 'admin\CarriageController@handle')->middleware('userpermisson');
Route::get('admin/carriage/del', 'admin\CarriageController@del')->middleware('userpermisson');

Route::get('admin/classify/lists', 'admin\ClassifyController@lists')->middleware(['adminlogin','userpermisson']);
Route::get('admin/classify/add', 'admin\ClassifyController@add')->middleware('adminlogin');
Route::get('admin/classify/edit', 'admin\ClassifyController@edit')->middleware('adminlogin');
Route::post('admin/classify/handle', 'admin\ClassifyController@handle')->middleware('userpermisson');
Route::get('admin/classify/del', 'admin\ClassifyController@del')->middleware('userpermisson');

Route::get('admin/dclassify/lists', 'admin\DclassifyController@lists')->middleware(['adminlogin','userpermisson']);
Route::get('admin/dclassify/add', 'admin\DclassifyController@add')->middleware('adminlogin');
Route::get('admin/dclassify/edit', 'admin\DclassifyController@edit')->middleware('adminlogin');
Route::post('admin/dclassify/handle', 'admin\DclassifyController@handle')->middleware('userpermisson');
Route::get('admin/dclassify/del', 'admin\DclassifyController@del')->middleware('userpermisson');

Route::get('admin/commodity/lists', 'admin\CommodityController@lists')->middleware(['adminlogin','userpermisson']);
Route::get('admin/commodity/add', 'admin\CommodityController@add')->middleware('adminlogin');
Route::get('admin/commodity/edit', 'admin\CommodityController@edit')->middleware('adminlogin');
Route::post('admin/commodity/handle', 'admin\CommodityController@handle')->middleware('userpermisson');
Route::get('admin/commodity/del', 'admin\CommodityController@del')->middleware('userpermisson');
Route::get('admin/commodity/remove', 'admin\CommodityController@remove')->middleware('userpermisson');
Route::get('admin/commodity/removes', 'admin\CommodityController@removes')->middleware('userpermisson');
Route::get('admin/commodity/ajaxDelPics', 'admin\CommodityController@ajaxDelPics')->middleware('userpermisson');
Route::get('admin/commodity/d_ajaxDelPics', 'admin\CommodityController@d_ajaxDelPics')->middleware('userpermisson');
Route::get('admin/commodity/ajaxDelLabel', 'admin\CommodityController@ajaxDelLabel')->middleware('userpermisson');

Route::get('admin/commoditycomment/lists', 'admin\CommodityCommentController@lists')->middleware(['adminlogin','userpermisson']);
Route::get('admin/commoditycomment/del', 'admin\CommodityCommentController@del')->middleware('userpermisson');

Route::get('admin/article/lists', 'admin\ArticleController@lists')->middleware(['adminlogin','userpermisson']);
Route::get('admin/article/edit', 'admin\ArticleController@edit')->middleware('adminlogin');
Route::get('admin/article/add', 'admin\ArticleController@add')->middleware('adminlogin');
Route::post('admin/article/handle', 'admin\ArticleController@handle')->middleware('userpermisson');

Route::get('admin/carousel/lists', 'admin\CarouselController@lists')->middleware(['adminlogin']);
Route::get('admin/carousel/add', 'admin\CarouselController@add')->middleware('adminlogin');
Route::get('admin/carousel/edit', 'admin\CarouselController@edit')->middleware('adminlogin');
Route::post('admin/carousel/handle', 'admin\CarouselController@handle')->middleware('userpermisson');
Route::get('admin/carousel/del', 'admin\CarouselController@del')->middleware('userpermisson');

Route::get('admin/dividedinto/lists', 'admin\DividedintoController@lists')->middleware(['adminlogin','userpermisson']);
Route::get('admin/dividedinto/fangkuan', 'admin\DividedintoController@fangkuan')->middleware('userpermisson');

Route::get('admin/dividedinto/lists2', 'admin\DividedintoController@lists2')->middleware(['adminlogin','userpermisson']);

Route::get('admin/config/edit', 'admin\ConfigController@edit')->middleware(['adminlogin','userpermisson']);
Route::post('admin/config/handle', 'admin\ConfigController@handle')->middleware('userpermisson');

Route::get('admin/menu/edit', 'admin\MenuController@edit')->middleware('adminlogin');
Route::post('admin/menu/save', 'admin\MenuController@save')->middleware('userpermisson');
Route::get('admin/menu/update', 'admin\MenuController@update')->middleware('userpermisson');
Route::get('admin/menu/del', 'admin\MenuController@del')->middleware('userpermisson');

Route::get('admin/baobiao/user', 'admin\BaobiaoController@user')->middleware('adminlogin');
Route::get('admin/baobiao/money', 'admin\BaobiaoController@money')->middleware('adminlogin');
Route::get('admin/baobiao/xiaoshou', 'admin\BaobiaoController@xiaoshou')->middleware('adminlogin');

Route::get('admin/order/lists', 'admin\OrderController@lists')->middleware(['adminlogin','userpermisson']);
Route::get('admin/order/listsOrder/{type}', 'admin\OrderController@listsOrder')->middleware('adminlogin');
Route::get('admin/order/detail', 'admin\OrderController@detail')->middleware(['adminlogin','userpermisson']);
Route::get('admin/order/fahuo', 'admin\OrderController@fahuo')->middleware('adminlogin');
Route::get('admin/order/cancelorder', 'admin\OrderController@cancelorder')->middleware('userpermisson');
Route::post('admin/order/handle', 'admin\OrderController@handle')->middleware('userpermisson');
Route::get('admin/order/export', 'admin\OrderController@export')->middleware('userpermisson');
Route::get('admin/order/message', 'admin\OrderController@message')->middleware('userpermisson');

Route::get('admin/orderrefunds/lists', 'admin\OrderRefundsController@lists')->middleware(['adminlogin','userpermisson']);
Route::get('admin/orderrefunds/agree', 'admin\OrderRefundsController@agree')->middleware('userpermisson');
Route::get('admin/orderrefunds/refuse', 'admin\OrderRefundsController@refuse')->middleware('userpermisson');

Route::get('admin/orderreturngoods/lists', 'admin\OrderReturnGoodsController@lists')->middleware(['adminlogin','userpermisson']);
Route::get('admin/orderreturngoods/detail', 'admin\OrderReturnGoodsController@detail')->middleware('adminlogin');
Route::get('admin/orderreturngoods/shouhuo', 'admin\OrderReturnGoodsController@shouhuo')->middleware('userpermisson');
Route::get('admin/orderreturngoods/agree', 'admin\OrderReturnGoodsController@agree')->middleware('userpermisson');
Route::get('admin/orderreturngoods/refuse', 'admin\OrderReturnGoodsController@refuse')->middleware('userpermisson');

Route::get('admin/msg/lists', 'admin\MsgController@lists')->middleware(['adminlogin','userpermisson']);
Route::get('admin/msg/update/{id?}', 'admin\MsgController@update')->middleware('adminlogin');
Route::get('admin/msg/add/{id?}', 'admin\MsgController@add')->middleware('adminlogin');
Route::post('admin/msg/handle', 'admin\MsgController@handle')->middleware('userpermisson');

Route::get('admin/design/lists', 'admin\DesignController@lists')->middleware(['adminlogin','userpermisson']);
Route::get('admin/design/detail', 'admin\DesignController@detail')->middleware('adminlogin');
Route::get('admin/design/del', 'admin\DesignController@del')->middleware('adminlogin');
Route::get('admin/design/details', 'admin\DesignController@details')->middleware('adminlogin');
Route::get('admin/design/agree', 'admin\DesignController@agree')->middleware('userpermisson');
Route::get('admin/design/refuse', 'admin\DesignController@refuse')->middleware('userpermisson');
Route::post('admin/design/handle', 'admin\DesignController@handle')->middleware('userpermisson');
Route::get('admin/error', 'admin\UserPermession@error')->middleware('adminlogin');


// 前端路由
//Route::group(['namespace' => 'FrontEnd'], function () {
//    Route::get('/', 'Page@home');
//    Route::get('/search', 'Page@search');
//    Route::get('/search-result/{type?}', 'Page@searchResult');
//    Route::get('/custom-submit', 'Page@customSubmit');
//    Route::get('/goods-submit', 'Page@goodsSubmit');
//    Route::get('/enterprise', 'Page@enterpriseOrder');
//    Route::get('/goods/{id}', 'Page@goodsDetail');
//    Route::get('/service', 'Page@service');
//    Route::get('/square', 'Page@square');
//    Route::get('/user', 'Page@user');
//    Route::get('/goods-list/{id?}', 'Page@goodsList');
//    Route::get('/fund-list/{id?}', 'Page@fundList');
//    Route::get('/fund/{id?}', 'Page@fundDetail');
//    Route::get('/order-list/{type?}', 'Page@orderList');
//    Route::get('/order/{id}', 'Page@orderDetail');
//    Route::get('/order/{id}/comment', 'Page@orderComment');
//    Route::get('/order/{id}/refund', 'Page@orderRefund');
//    Route::get('/my-fund', 'Page@myFund');
//});

// 前端路由
Route::group(['namespace' => 'FrontEnd'], function () {
    Route::get('/','Page@home');
    Route::get('/search','Page@search');
    Route::get('/test','Page@test');
    Route::get('/wechatLogin','Page@wechatLogin');
    Route::get('/search-result/{type?}/{keyword?}','Page@searchResult');
    Route::get('/custom-submit','Page@customSubmit');
    Route::get('/goods-submit','Page@goodsSubmit');
    Route::get('/enterprise','Page@enterpriseOrder');
    Route::get('/goods/{id}/{past?}','Page@goodsDetail');
    Route::get('/service','Page@service');
    Route::get('/square','Page@square');
    Route::get('/searchDelete','Page@searchDelete');
    Route::get('/user','Page@user');
    Route::get('/goods-list/{id?}/{past?}/{type?}','Page@goodsList');
    Route::get('/fund-list/{id?}/{past?}/{type?}','Page@fundList');
    Route::get('/fund/{id?}','Page@fundDetail');
    Route::get('/order-list/{type?}','Page@orderList');
    Route::get('/order/{id}','Page@orderDetail');
    Route::get('/pay','Page@pay');
    Route::get('/order/{id}/comment','Page@orderComment');
    Route::get('/order/{id}/refund','Page@orderRefund');
    Route::get('/my-fund','Page@myFund');
    Route::get('/award','Page@myAward');
    Route::get('/award/withdraw','Page@awardWithdraw');
    Route::get('/award/list','Page@awardList');
    Route::get('/attention','Page@attention');
    Route::get('/message','Page@message');
    Route::get('/message/{id}','Page@messageDetail');
    Route::get('/address','Page@address');
    Route::get('/address/new','Page@addressNew');
    Route::get('/address/edit','Page@addressEdit');
    Route::get('/evaluate/{id}','Page@evaluate');
    Route::get('/explain','Page@explain');
    Route::get('/getUserOpenId','Page@getUserOpenId');
    Route::get('/goods-order', 'Page@goodsOrder');
    Route::get('/getcookie', 'Page@getcookie');
    Route::get('/cart','Page@cart');
    Route::get('/wuliu','Page@wuliu');
    Route::get('/wuliuList','Page@wuliuList');
//    Route::post('/goods-order', 'Page@goodsOrder');
    Route::post('/savetofile','Page@SaveToFile');
});
Route::post('/home/design/handle', 'home\DesignController@designHandle');
Route::post('/home/design/custom', 'home\DesignController@custom');
Route::get('/home/design/hotes', 'home\DesignController@hotes');
Route::get('/home/order/cancle', 'home\OrderController@cancle');
Route::post('/home/order/evaluate', 'home\OrderController@evaluate');
Route::get('/home/order/refund', 'home\OrderController@refund');
Route::get('/home/order/shouhuo', 'home\OrderController@shouhuo');
Route::get('/home/goods/love', 'home\GoodsController@love');
Route::post('/home/address/add', 'home\AddressController@add');
Route::get('/home/address/setdefault', 'home\AddressController@setdefault');
Route::get('/home/address/set_address_select', 'home\AddressController@set_address_select');
Route::post('/home/address/edit', 'home\AddressController@edit');
Route::post('/home/order/confirm', 'home\OrderController@confirm');
Route::any('/home/order/wechatPay', 'home\OrderController@wechatPay');
Route::get('/home/address/del', 'home\AddressController@del');
Route::get('/home/address/cale', 'home\AddressController@cale');
Route::get('/home/goods/cancel-love', 'home\GoodsController@cancelLove');
Route::post('/home/WxPay/WxPay', 'home\WxPayController@WxPay');
Route::any('/home/WxPay/WxPay2', 'home\WxPayController@WxPay2');
Route::any('/home/WxPay/getOrder', 'home\WxPayController@getOrder');
Route::get('/home/WxPay/withDraw', 'home\WxPayController@withDraw');
//ajax购物车操作路由
Route::get('/home/cart/ajaxPlusCount', 'home\CartController@ajaxPlusCount');
Route::get('/home/cart/ajaxMinusCount', 'home\CartController@ajaxMinusCount');
Route::get('/home/cart/ajaxDelCart', 'home\CartController@ajaxDelCart');
Route::post('/home/cart/ajaxAddCart', 'home\CartController@ajaxAddCart'); 

//Route::group(['middleware' => ['web', 'wechat.oauth']], function () {
//    Route::get('/user', function () {
//        $user = session('wechat.oauth_user'); // 拿到授权用户资料
//        dd($user);
//        return view('user',$user);
//    });
//});Route::get('/user', ['middleware' => 'wechat.oauth', function () {
//    $user = session('wechat.oauth_user'); // 拿到授权用户资料
//        dd($user);
//}]);
//
