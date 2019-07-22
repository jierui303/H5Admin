<?php
/**
 * 我的个人中心
 * wangruijie
 */
namespace App\Http\Controllers\H5api;

use App\Http\Controllers\CommanController;
use App\Http\Models\CleanModel;
use Illuminate\Http\Request;

class MimeController extends CommanController
{
    protected $cleanModel;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->cleanModel = new CleanModel();
    }

    /**
     *我的个人中心
     */
    public function mime()
    {
        try{
            $cleanId = $this->request->get('id');//保洁员id
            if(empty($cleanId)){
                return '保洁员id为空';
            }

            $cleanInfo = $this->cleanModel->getCleanInfoById($cleanId, [
                'realname',
                'phone',
                'thumb',
                'provinceid'
            ]);
            if(empty($cleanInfo)){
                return '保洁员信息不存在';
            }

            return $cleanInfo;

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
