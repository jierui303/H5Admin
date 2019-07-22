<?php
/**
 * wangruijie
 */
namespace App\Http\Controllers\H5api;

use App\Http\Controllers\CommanController;
use App\Http\Models\OrderModel;
use App\Http\Models\SuitModel;
use Illuminate\Http\Request;

class FinishedOrderInfoController extends CommanController
{
    protected $orderModel;
    protected $suitModel;

    protected $gid;//工单id
    protected $yid;//公寓id

    private $data;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->orderModel = new OrderModel();
        $this->suitModel = new SuitModel();
    }

    /**
     *已完成工单详情页
     */
    public function getFinishedOrderInfo()
    {
        try{

            $this->gid = $this->request->get('gid');//工单id
            if(empty($this->gid)){
                throw new \ErrorException('工单id不能为空', 500);
            }

            self::getOrderInfo();

            return $this->data;

        }catch (\ErrorException $errorException){
            return array(
                'msg' => $errorException->getMessage(),
                'code' => $errorException->getCode(),
                'file' => $errorException->getFile(),
                'line' => $errorException->getLine()
            );
        }

    }

    private function getOrderInfo()
    {
        try{

            //工单表
            $orderInfo = $this->orderModel->getOneByWhere([
                'gid'=>$this->gid
            ], [
                'btype', //保洁类型
                'wholeRent', //合租还是个人
                'status', //工单状态
                'onlineReservation', //是否线上预约
                'stimestart', //上门服务周期的起始时间
                'stimeend', //上门服务周期的截止时间
                'duration', //租户预约时段
                'servicePeriod', //预约服务时长
                'bnames', //保洁员[真实名称][存在多个保洁员]
                'planservicetime', //计划上门日期
                'timeperiod', //计划上门时段
                'servicestarttime', //保洁开始时间
                'serviceEndTime', //保洁结束时间
                'createdAt', //工单创建时间
                'des', //工单备注
                'gid', //工单编号
                'yid', //公寓编号
                'companyid', //公司名称
                'suiteSource', //房源
                'createPerson', //创建人联系方式
                'stateTag', //标签
                'rentFlag', //1:已出租，2：可出租，3：可预订
                'gtype', //公寓类型
                'designcontact', //设计师联系方式
            ]);

            if(empty($orderInfo)){
                throw new \ErrorException('当前工单信息不存在', 10011);
            }

            $this->yid = $orderInfo['yid'];

            //预约信息
            $this->data['orderInfo'] = [
                'btype' => $orderInfo['btype'], //保洁类型
                'wholeRent' => $orderInfo['wholeRent'], //合租还是个人
                'stimestart' => $orderInfo['stimestart'], //上门服务周期的起始时间
                'stimeend' => $orderInfo['stimeend'], //上门服务周期的截止时间
                'duration' => $orderInfo['duration'], //租户预约时段
                'servicePeriod' => $orderInfo['servicePeriod'], //预约服务时长
                'bnames' => $orderInfo['bnames'], //保洁员[真实名称][存在多个保洁员]
                'planservicetime' => $orderInfo['planservicetime'], //计划上门日期
                'timeperiod' => $orderInfo['timeperiod'], //计划上门时段
                'servicestarttime' => $orderInfo['servicestarttime'], //保洁开始时间
                'serviceEndTime' => $orderInfo['serviceEndTime'], //保洁结束时间
                'createdAt' => $orderInfo['createdAt'], //工单创建时间
            ];

            //工单备注 这个des字段里面保存了两种字段类型数据 一个是工单备注,一个是派单说明
            $this->data['orderRemarkInfo'] = $orderInfo['des'];

            //基础信息
            $this->data['orderBaseInfo'] = [
                'gid' => $orderInfo['gid'], //工单编号
                'yid' => $orderInfo['yid'], //公寓编号
                'gtype' => $orderInfo['gtype'], //类型
                'paiDanDes' => $orderInfo['des'], //派单说明
            ];

            //联系方式
            $this->data['orderContact'] = [
                'createdAt' => $orderInfo['createdAt'], //工单创建时间
                'designContact'=> $orderInfo['designcontact'], //设计师联系方式
            ];

        }catch (\ErrorException $errorException){
            return array(
                'msg' => $errorException->getMessage(),
                'code' => $errorException->getCode(),
                'file' => $errorException->getFile(),
                'line' => $errorException->getLine()
            );
        }

    }

    private function getSuitInfo()
    {
        //公寓表
        $suitInfo = $this->suitModel->getOneByWhere([
            'from_id'=>$this->yid
        ], [
            'btype', //保洁类型
            'homeArea', //公寓面积
            'village', //小区名称
            'address', //小区地址
            'roomid', //房间号
            'blockid', //商圈id
        ]);

        //基础信息
        $orderBaseInfo = [
            'homeArea' => $suitInfo['homeArea'], //公寓面积
            'village' => $suitInfo['village'], //小区名称
            'address' => $suitInfo['address'], //小区地址
            'roomid' => $suitInfo['roomid'], //房间号
            'blockid' => $suitInfo['blockid'], //所在商圈
        ];

        $this->data['orderBaseInfo'] = array_merge($this->data['orderBaseInfo'], $orderBaseInfo);

    }

}
