<?php

namespace App\Http\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

class CleanModel extends Authenticatable implements JWTSubject
{
    use Notifiable;
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'clean';
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = [
        '',
    ];


    /**
     * 描述:jwt返回token
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * 描述:通过保洁员手机号查询指定字段信息
     * @param $mobile
     * @param $param
     * @return mixed
     * created on 2019/4/27 16:36
     * created by wangruijie
     */
    public function getCleanerByMobile($mobile, $param)
    {
        return $this->where('phone', $mobile)
            ->select($param)
            ->first()
            ->toArray();
    }

    /**
     * 描述:指定多个查询条件 查询多个字段
     * @param $where
     * @param $param
     * @return mixed
     * created on 2019/4/27 16:37
     * created by wangruijie
     */
    public function getCleanerByWhere($where, $param)
    {
        return $this->where($where)
            ->limit(2)
            ->get($param)
            ->toArray();
    }

    /**
     * 描述:通过保洁员id获取指定字段信息
     * @param $cleanId
     * @param array $columns
     * @return mixed
     * created on 2019/4/27 16:35
     * created by wangruijie
     */
    public function getCleanInfoById($cleanId, array $columns = ['*'])
    {
        return $this->where('id', $cleanId)
            ->select($columns)
            ->first()
            ->toArray();
    }
}
