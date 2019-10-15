<?php
/**
 * wangruijie
 */
namespace App\Http\Controllers\V1\Controller\order;

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
        $books = [
            ['id'=>1, 'title'=>'Hogfather', 'characters' => ['id'=>1, 'title'=>'Hogfather']],
            ['id'=>2, 'title'=>'Game Of Kill Everyone', 'characters' => ['id'=>1, 'title'=>'Hogfather']]
        ];

        return response()->json($books);
//        return 'v1-test';
    }

}
