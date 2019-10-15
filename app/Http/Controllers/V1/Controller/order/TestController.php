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

/**
 * @api {POST} http://aaa.com/index/Index/add_needs 测试接口文档 APIDoc示例
 * @apiVersion 1.0.0
 * @apiGroup NEED
 *
 * @apiParam {String} need_name 需求者名称-非空
 * @apiParam {String} e_mail 用户邮箱-非空邮箱格式
 * @apiParam  {String} phone 用户电话-非空
 * @apiParam {String} company_name 需求公司名称-非空
 * @apiParam  {String} needs_desc 需求描述-非空
 *
 * @apiSuccess {Object} code 返回码
 * @apiSuccess {Object} reason  中文解释
 * @apiSuccess {String[]} data  返回数据
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *          "code":0,
 *          "reason":"需求已经提交了，我们的工作人员会在2个工作日内和您取得联系!",
 *          "data":[]
 *      }
 */
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
