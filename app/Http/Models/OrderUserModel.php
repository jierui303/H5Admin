<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class OrderUserModel extends Model
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'order_user';

    public function getAllByWhere($where, $perPage = 20, $orderBy = [], array $columns = ['*'])
    {
        $res = $this->where($where)->select($columns);

        if (empty($orderBy) === false) {

            foreach ($orderBy as $field => $order) {
                $res = $res->orderBy($field, $order);
            }

        }

        return $res->paginate($perPage);
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
