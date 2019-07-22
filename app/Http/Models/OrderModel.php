<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'order';


    /**
     * 描述:指定多个查询条件 查询多个字段 支持分页
     * @param $where
     * @param int $perPage
     * @param array $orderBy
     * @param array $columns
     * @return mixed
     * created on 2019/4/27 16:39
     * created by wangruijie
     */
    public function getAllByWhere($where, $currentPage, $perPage = 20, $orderBy = [], array $columns = ['*'])
    {
        $obj = $this->where($where);

        if (empty($orderBy) === false) {

            foreach ($orderBy as $field => $order) {
                $obj = $obj->orderBy($field, $order);
            }

        }

        return $obj->paginate($perPage, $columns, 'page', $currentPage);
    }

    /**
     * 描述:指定多个查询条件 查询多个字段 不支持分页
     * @param $where
     * @param array $orderBy
     * @param array $columns
     * @return mixed
     * created on 2019/4/27 16:55
     * created by wangruijie
     */
    public function getAllByWhereNoPaginate($where, $orderBy = [], array $columns = ['*'])
    {
        $res = $this->where($where)->select($columns);

        if (empty($orderBy) === false) {

            foreach ($orderBy as $field => $order) {
                $res = $res->orderBy($field, $order);
            }

        }

        return $res->get();
    }

    /**
     * 描述:根据多条件 获取指定字段 只取一条
     * @param $where
     * @param array $columns
     * @return mixed
     * created on 2019/4/27 18:34
     * created by wangruijie
     */
    public function getOneByWhere($where, array $columns = ['*'])
    {
        return $this->where($where)
            ->select($columns)
            ->first();
    }
}
