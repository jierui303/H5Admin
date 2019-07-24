<?php
/**
 * wangruijie
 */
namespace App\Http\Controllers\Test;

use App\Http\Controllers\CommanController;
use App\Http\Enums\DefineEnums;
use App\Http\Models\AreaModel;
use App\Http\Models\OrderModel;
use Illuminate\Http\Request;
use Wrj\Order;

class TestController extends CommanController
{

    public function __construct(Request $request)
    {
        parent::__construct($request);

    }

    public function aaa()
    {
        $orderObj = new Order(1, 2);//实例化composer自定义包中的类
        echo $orderObj->addTogether();
    }

}
