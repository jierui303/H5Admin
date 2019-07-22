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

class AllocatedOrderController extends CommanController
{
    protected $orderModel;
    protected $areaModel;

    protected $gid;//工单id
    protected $yid;//公寓id
    protected $blockid;//商圈id
    protected $stimestart;//服务起始时间 接收时间戳
    protected $stimeend;//服务结束时间 接收时间戳

    protected $page;//当前页码
    protected $perPage = 20;//多少条分页

    private $output = [
        'page' => [],
        'list' => []
    ];

    private $getInfo;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->orderModel = new OrderModel();
        $this->areaModel = new AreaModel();
    }

    /**
     *获取待分配订单列表
     */
    public function getAllocatedOrderLists()
    {
        try{
            $this->page = $this->request->get('page');//页码
            if(empty($this->page)){
                $this->page = 1;
            }

            $this->gid = $this->request->get('gid');//工单id
            if(!empty($this->gid)){
                $where['gid'] = (int)$this->gid;
            }

            $this->yid = $this->request->get('yid');//公寓id
            if(!empty($this->yid)){
                $where['yid'] = (int)$this->yid;
            }

            $this->blockid = $this->request->get('blockid');//商圈id
            if(!empty($this->blockid)){
                $where['blockid'] = (int)$this->blockid;
            }

            $this->stimestart = $this->request->get('stimestart');//起始时间 接收时间戳
            $this->stimeend = $this->request->get('stimeend');//结束时间 接收时间戳
            if($this->stimestart && $this->stimeend){
                $where['stimestart'] = ['<', $this->stimestart];
                $where['stimeend'] = ['>', $this->stimeend];
            }

            $where['isdel'] = 1;
            $where['status'] = 1;

            $res = self::getAllOrders($where);

            $this->transFormation($res);

            return $this->output;

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
     * 查询待分配订单列表
     * @param $where
     * @return array
     */
    private function getAllOrders($where)
    {
        $this->getInfo = $this->orderModel->getAllByWhere(
            $where,
            $this->page,
            $this->perPage,
            [], [
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
            'roomnumber' //房间号
        ]);

        //处理数据
        $newData = [];
        if(!empty($this->getInfo)){

            foreach ($this->getInfo as $k=>$v){

                $newData[$k]['gid'] = $v['gid'];//工单号

                $newData[$k]['yid'] = $v['yid'];//公寓号

                $newData[$k]['serviceTime'] = date('m-d', $v['stimestart']).'至'.date('m-d', $v['stimeend']);//上门周期

                $newData[$k]['btype'] = !empty($v['btype']) ? DefineEnums::CLEANTYPE[$v['btype']] : '';//保洁类型

                $newData[$k]['rentFlag'] = ($v['rentFlag'] >= 1) ? DefineEnums::RENTFLAG[$v['rentFlag']] : '';//1:已出租，2：可出租，3：可预订

                $newData[$k]['wholeRent'] = ($v['wholeRent'] >= 1) ? DefineEnums::WHOLERENT[$v['wholeRent']] : '';//整租还是合租

                $newData[$k]['tradingArea'] = self::getAres($v);//商圈

                $newData[$k]['addressHome'] = '#'.$v['yid'].' '.$v['villageaddress'].'-'.$v['roomnumber'];//地址-房间号

                $newData[$k]['remarks'] = $v['des'];//备注信息

            }

        }

        return $newData;
    }

    private function getAres($v)
    {
        $ids = [$v['cityid'], $v['areaid'], $v['blockid']];

        $tradingAres = $this->areaModel->getAllsByWhere($ids, [
            'name','id'
        ]);

        $names = array_column($tradingAres, 'name', 'id');

        return join('-', $names);
    }

    private function transFormation($res)
    {
        $this->output['page'] = [
            "perPage" => $this->perPage,
            "currentPage"=> $this->page,
        ];
        $this->output['list']= $res;
    }

}
