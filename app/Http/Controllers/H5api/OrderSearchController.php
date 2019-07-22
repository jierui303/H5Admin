<?php

namespace App\Http\Controllers\H5api;

use App\Http\Controllers\CommanController;
use App\Http\Enums\DefineEnums;
use App\Http\Models\AreaModel;
use App\Http\Models\OrderModel;
use App\Http\Models\OrderQuitModel;
use App\Http\Models\OrderRelayModel;
use App\Http\Models\OrderUserModel;
use App\Http\Models\SuitModel;
use Illuminate\Http\Request;

class OrderSearchController extends CommanController
{
    protected $orderModel;
    protected $areaModel;
    protected $orderQuitModel;
    protected $orderRelayModel;
    protected $orderUserModel;
    protected $suitModel;

    protected $gid;//工单id
    protected $yid;//公寓id

    private $getInfo;

    private $data;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->orderModel = new OrderModel();
        $this->areaModel = new AreaModel();
        $this->orderQuitModel = new OrderQuitModel();
        $this->orderRelayModel = new OrderRelayModel();
        $this->orderUserModel = new OrderUserModel();
        $this->suitModel = new SuitModel();
    }

    /**
     *获取待分配订单列表
     */
    public function orderSearchLists()
    {
        try{
            $type = $this->request->get('type');//是1工单查询 or 2公寓查询
            if(empty($type)){
                return '缺少工单还是公寓查询参数';
            }

            $where['isdel'] = 1;

            //判断是工单id还是公寓id
            if($type == 1){

                //工单id 查看工单详情
                $this->gid = $this->request->get('gid');//工单id
                if(empty($this->gid)){
                    return '工单id不能为空';
                }

                self::getOrderInfo();

                return $this->data;

            }else{

                //公寓id 展示工单列表
                $this->yid = $this->request->get('yid');//公寓id
                if(!empty($this->yid)){
                    $where['yid'] = (int)$this->yid;
                }

                return self::getAllOrders($where);

            }

        }catch (\ErrorException $errorException){
            return array(
                'msg' => $errorException->getMessage(),
                'code' => $errorException->getCode(),
                'file' => $errorException->getFile(),
                'line' => $errorException->getLine()
            );
        }

    }

    /**
     * 描述:通过工单id获取到工单详细信息
     * @throws \ErrorException
     * created on 2019/4/27 19:21
     * created by wangruijie
     */
    private function getOrderInfo()
    {
        self::getOrderListsByGid();//查询工单表

        self::getOrderQuitInfo();//获取退单表

        self::getOrderRelayInfo();//获取延期表信息

        self::getSuitInfo();//获取公寓表信息

        self::getOrderUserInfo();//获取派单表信息
    }

    private function getOrderUserInfo()
    {
        //派单表
        $orderUserInfo = $this->orderUserModel->getOneByWhere([
            'gid'=>$this->gid
        ], [
            'bid', //保洁员id
            'flag', //1：派单，2：转派
            'createtime', //分派时间
        ]);

        //派单以及转派明细
        $this->data['orderUserInfo'] = [
            'reason' => $orderUserInfo['reason'], //操作人
            'did' => $orderUserInfo['did'], //派给
            'updatetime' => $orderUserInfo['updatetime'], //分派时间
        ];
    }

    private function getOrderQuitInfo()
    {
        //退单表
        $orderQuitInfo = $this->orderQuitModel->getOneByWhere([
            'oid'=>$this->gid
        ], [
            'did', //调度员id
            'reason', //退单原因
            'updatetime', //退单时间
        ]);

        //退单明细
        $this->data['orderQuitInfo'] = [
            'reason' => $orderQuitInfo['reason'], //退单原因
            'did' => $orderQuitInfo['did'], //操作人
            'updatetime' => $orderQuitInfo['updatetime'], //退单时间
        ];
    }

    private function getOrderRelayInfo()
    {
        //改期延迟表
        $orderRelayInfo = $this->orderRelayModel->getOneByWhere([
            'oid'=>$this->gid
        ], [
            'did', //调度员id
            'reason', //改期原因
            'updatetime', //改期时间
            'reason', //改期备注
        ]);

        //延期明细
        $this->data['orderRelayInfo'] = [
            'reason' => $orderRelayInfo['reason'], //延期原因
            'did' => $orderRelayInfo['did'], //操作人
            'updatetime' => $orderRelayInfo['updatetime'], //操作时间
            'relayDes' => $orderRelayInfo['reason'], //延期备注
        ];
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

    /**
     * 描述:查询工单表
     * @throws \ErrorException
     * created on 2019/4/27 19:19
     * created by wangruijie
     */
    private function getOrderListsByGid()
    {
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
        ]);

        if(empty($orderInfo)){
            throw new \ErrorException('当前工单信息不存在', 10011);
        }

        $this->yid = $orderInfo['yid'];

        //预约信息
        $this->data['orderInfo'] = [
            'btype' => $orderInfo['btype'], //保洁类型
            'wholeRent' => $orderInfo['wholeRent'], //合租还是个人
            'status' => $orderInfo['status'], //工单状态
            'onlineReservation' => $orderInfo['onlineReservation'], //是否线上预约
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
            'companyid' => $orderInfo['companyid'], //公司名称
            'suiteSource' => $orderInfo['suiteSource'], //房源
            'createPerson' => $orderInfo['createPerson'], //创建人联系方式
            'stateTag' => $orderInfo['stateTag'], //标签
            'gtype' => $orderInfo['gtype'], //类型
            'paiDanDes' => $orderInfo['des'], //派单说明
        ];
    }

    /**
     * 描述:查询所有工单列表
     * @param $where
     * @return array
     * created on 2019/4/27 17:02
     * created by wangruijie
     */
    private function getAllOrders($where)
    {
        $this->getInfo = $this->orderModel->getAllByWhereNoPaginate($where, [], [
            'gid', //工单号
            'yid', //公寓id
            'roomid', //房间id
            'stimestart', //上门服务周期的起始时间
            'stimeend', //上门服务周期的截止时间
            'btype', //保洁类型
            'gtype', //公寓类型
            'ftype', //房态类型
            'cityid', //城市
            'areaid', //区域
            'blockid', //商圈
            'wholeRent', //1：整租，2：合租
            'rentFlag', //1:已出租，2：可出租，3：可预订
            'des', //备注
            'villageaddress', //小区地址
            'roomnumber', //房间号
            'status', //工单状态
        ]);

        //处理数据
        $newData = [];
        if(!empty($this->getInfo)){

            foreach ($this->getInfo as $k=>$v){

                if($v['status'] == 1){//待分配

                    $newData['allocatedOrder']['groupName'] = '待分配工单';
                    $newData['allocatedOrder']['data'][] = self::getNewOrderLists($v);

                } elseif ($v['status'] == 2){//待上门

                    $newData['waitDoorOrder']['groupName'] = '待上门工单';
                    $newData['waitDoorOrder']['data'][] = self::getNewOrderLists($v);

                }elseif ($v['status'] == 3) {//已完成

                    $newData['finishedOrder']['groupName'] = '已完成工单';
                    $newData['finishedOrder']['data'][] = self::getNewOrderLists($v);

                }elseif ($v['status'] == 4) {//退单

                    $newData['backOrder']['groupName'] = '退单工单';
                    $newData['backOrder']['data'][] = self::getNewOrderLists($v);

                }

                continue;
            }

        }

        return $newData;
    }

    private function getNewOrderLists($v)
    {
        $data = [];

        $data['gid'] = $v['gid'];//工单号

        $data['yid'] = $v['yid'];//公寓号

        $data['cleanRealName'] = $v['bnames'];//保洁员名称

        $data['btype'] = !empty($v['btype']) ? DefineEnums::CLEANTYPE[$v['btype']] : '';//保洁类型

        $data['rentFlag'] = ($v['rentFlag'] >= 1) ? DefineEnums::RENTFLAG[$v['rentFlag']] : '';//1:已出租，2：可出租，3：可预订

        $data['wholeRent'] = ($v['wholeRent'] >= 1) ? DefineEnums::WHOLERENT[$v['wholeRent']] : '';//整租还是合租

        $data['tradingArea'] = self::getAres($v);//商圈

        $data['addressHome'] = '#'.$v['yid'].' '.$v['villageaddress'].'-'.$v['roomnumber'];//地址-房间号

        $data['remarks'] = $v['des'];//备注信息

        return $data;
    }

    /**
     * 描述:根据城市,区域,商圈获取相应名称
     * @param $cityid 城市
     * @param $areaid 区域
     * @param $blockid 商圈
     * @return string
     * created on 2019/4/27 17:04
     * created by wangruijie
     */
    private function getAres($v)
    {
        $ids = [$v['cityid'], $v['areaid'], $v['blockid']];

        $tradingAres = $this->areaModel->getAllsByWhere($ids, [
            'name','id'
        ]);

        $names = array_column($tradingAres, 'name', 'id');

        return join('-', $names);
    }

}
