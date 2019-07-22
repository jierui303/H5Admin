<?php
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Route;

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

\Illuminate\Support\Facades\Route::group([
    'middleware' => [
        'web',
        'loginApiSign' //验证token中间件
    ]
], function($router){

    $router->get('/orderSearchLists', 'H5api\OrderSearchController@orderSearchLists');//搜索入口

    $router->get('/getAllocatedOrderLists', 'H5api\AllocatedOrderController@getAllocatedOrderLists');//获取待分配工单列表
    $router->get('/getAllocatedOrderInfo', 'H5api\AllocatedOrderInfoController@getAllocatedOrderInfo');//待分配工单详情页


    $router->get('/getWaitDoorOrderLists', 'H5api\WaitDoorOrderController@getWaitDoorOrderLists');//获取待上门工单列表
    $router->get('/getWaitDoorOrderInfo', 'H5api\WaitDoorOrderInfoController@getWaitDoorOrderInfo');//待上门工单详情页


    $router->get('/getFinishedOrderLists', 'H5api\FinishedOrderController@getFinishedOrderLists');//获取已完成工单列表
    $router->get('/getFinishedOrderInfo', 'H5api\FinishedOrderInfoController@getFinishedOrderInfo');//已完成工单详情页


    $router->get('/getBackOrderLists', 'H5api\BackOrderController@getBackOrderLists');//获取退单中工单列表

    $router->get('/mime', 'H5api\MimeController@mime');//我的个人中心


    $router->post('/dispatchOrder', 'H5api\DispatchOrderController@dispatchOrder');//派单接口

});
