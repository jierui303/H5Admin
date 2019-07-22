<?php
/**
 * wangruijie
 */
namespace App\Http\Controllers\H5api;

use App\Http\Controllers\CommanController;
use App\Http\Enums\DefineEnums;
use App\Http\Models\AreaModel;
use App\Http\Models\OrderModel;
use App\Http\Models\OrderUserModel;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DataStatisticsController extends CommanController
{
    protected $orderModel;
    protected $areaModel;

    protected $realName;//保洁员名称
    protected $yid;//公寓id
    protected $blockid;//商圈id
    protected $stimestart;//服务起始时间 接收时间戳
    protected $stimeend;//服务结束时间 接收时间戳
    protected $status;//工单状态

    protected $page;//第几页

    private $output = [
        'page' => [],
        'list' => []
    ];

    private $getInfo;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->orderModel = new OrderUserModel();
        $this->areaModel = new AreaModel();
    }

    /**
     *获取待上门订单列表
     */
    public function dataStatistics()
    {
        try{
            $this->page = $this->request->get('page');//页码
            if(empty($this->page)){
                $this->page = 1;
            }

            $this->realName = $this->request->get('realName');//保洁员名称
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

            $this->status = (int)$this->request->get('status');
            if($this->status){
                $where['status'] = $this->status;
            }else{
                return '工单受理状态不能为空';
            }

            $where['isdel'] = 1;

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
     * 查询所有订单列表
     * @param $where
     * @return array
     */
    private function getAllOrders($where)
    {
        $this->getInfo = $this->orderModel->getAllByWhere($where, 20, [], [
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

                $newData[$k]['yid'] = $v['yid'];//公寓号
                $newData[$k]['serviceTime'] = date('m-d', $v['stimestart']).'至'.date('m-d', $v['stimeend']);//上门周期
                $newData[$k]['orderType'] = self::getOrderType($v['btype'], $v['wholeRent'], $v['rentFlag'], $v['ftype']);
                $newData[$k]['tradingArea'] = self::getAres($v['cityid'], $v['areaid'], $v['blockid']);//商圈
                $newData[$k]['addressHome'] = $v['villageaddress'].'-'.$v['roomnumber'];//地址-房间号
                $newData[$k]['remarks'] = $v['des'];//备注信息

            }

        }

        return $newData;
    }

    private function getAres($cityid, $areaid, $blockid)
    {
        $ids = [$cityid, $areaid, $blockid];

        $tradingAres = $this->areaModel->getAllsByWhere($ids, [
            'name','id'
        ]);

        $names = array_column($tradingAres, 'name', 'id');

        return join('-', $names);
    }

    private function getOrderType($btype, $wholeRent, $rentFlag, $ftype)
    {
        $btype = isset($btype) ? DefineEnums::CLEANTYPE[$btype] : '';
        $wholeRent = isset($wholeRent) ? DefineEnums::WHOLERENT[$wholeRent] : '';
//        $rentFlag = $rentFlag != 0 ? DefineEnums::RENTFLAG[$rentFlag] : '';
        $ftype = isset($ftype) ? DefineEnums::FTYPE[$ftype] : '';

        //类型 退转换保洁-合租-可出租-民宅平层-无数据
        return $btype.'-'. $wholeRent.'-'. $rentFlag.'-'. $ftype.'-'. '无数据';
    }

    private function transFormation($res)
    {
        $this->output['page'] = [
            "perPage" => (string) 1,
            "currentPage"=> request()->input('page', 1),
            'lastPage' => isset($this->getInfo['totalPage']) ? $this->getInfo['totalPage'] : 0
        ];
        $this->output['list']= $res;
    }

}
