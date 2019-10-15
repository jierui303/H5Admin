<?php

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    $router->post('login', 'AuthController@login')->name('login');
    $router->post('logout', 'AuthController@logout')->name('logout');
    $router->post('refresh', 'AuthController@refresh')->name('refresh');
    $router->post('me', 'AuthController@me')->name('me');
});



include dirname (__FILE__).'/v1/v1.php';//引入v1版本路由




$api = app('Dingo\Api\Routing\Router');


#登录获取token
//$api->version('v1', [
//    'middleware' => 'api',
//    'prefix' => 'auth'
//], function ($api) {
//    $api->post('login', 'AuthController@login');
//});


#携带token进行校验
$api->version('v1', [
    'middleware' => ['web', 'loginApiSign']
], function ($api) {
    $api->post('test', 'App\Http\Controllers\V1\order\TestController@aaa');
//    $api->post('test', function (){
//        return response('this is v1-test');
//    });
});


$api->version('v2', [
    'middleware' => ['web', 'loginApiSign']
], function ($api) {
    $api->post('test', 'App\Http\Controllers\V2\order\TestController@aaa');
//    $api->post('test', function (){
//        return response('this is v2-test');
//    });
});

//$api->version('v2', function ($api) {
//    $api->get('test', 'App\Api\V1\Controllers\UserController@show');
//    $api->get('test', function (){
//        return response('this is v2-test');
//    });
//});


