<?php
/**
 * wangruijie
 */
namespace App\Http\Controllers\H5api;

use App\Http\Controllers\CommanController;
use App\Http\Enums\DefineEnums;
use App\Http\Models\AreaModel;
use App\Http\Models\OrderModel;
use Illuminate\Http\Request;

class DispatchOrderController extends CommanController
{
    protected $orderModel;
    protected $areaModel;

    protected $gid;//工单id
    protected $yid;//公寓id
    protected $blockid;//商圈id

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->orderModel = new OrderModel();
        $this->areaModel = new AreaModel();
    }

    /**
     * 派单接口
     */
    public function dispatchOrder()
    {

        try{


            return [];

        }catch (\ErrorException $errorException){
            return array(
                'msg' => $errorException->getMessage(),
                'code' => $errorException->getCode(),
                'file' => $errorException->getFile(),
                'line' => $errorException->getLine()
            );
        }

    }

}
