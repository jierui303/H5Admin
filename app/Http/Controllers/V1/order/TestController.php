<?php
/**
 * wangruijie
 */
namespace App\Http\Controllers\V1\order;

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
        return 'v1-test';
    }

}
