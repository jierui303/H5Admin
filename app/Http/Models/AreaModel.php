<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class AreaModel extends Model
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'area';

    public function getAllsByWhere($where, $param)
    {
        return $this->whereIn('id', $where)
            ->get($param)
            ->toArray();
    }

}
