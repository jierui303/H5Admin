<?php

namespace App\Http\Enums;


class DefineEnums
{
    //保洁类型
    const CLEANTYPE = array(
        '1' => '双周保洁',
        '2' => '退转换保洁',
        '3' => '开荒保洁',
        '4' => '日常保洁',
        '5' => '紧急保洁',
        '6' => '消杀保洁',
        '7' => '维修后保洁',
        '8' => '返还深度保洁',
        '9' => '非开荒保洁',
        '10' => '空气质量测试',
        '11' => '空气质量整治',
        '12' => '空气质量验证',
    );

    //工单状态cleanstatus
    const CLEANSTATUS= array(
        '1' => '待分配',
        '2' => '待上门',
        '3' => '已完成',
        '4' => '已退单',
        '5' => '改期',
        '6' => '客户原因未完成',
        '8' => '工单已撤销',
    );
    //备选印象标签impression
    const IMPRESSION = array(
        'good' => [
            1 => '很干净',
            2 => '很整齐',
            3 => '位置好',
            4 => '东西少',
            ],
        'bad' => [
            1 => '脏',
            2 => '乱',
            3 => '差',
            ],
    );

    const IMPRESS = [
        [
            ['id' => 1,'item' => '重点对象']
            , ['id' => 2,'item' => '要求女保洁']
            , ['id' => 3,'item' => '有洁癖']
            , ['id' => 4,'item' => '特殊要求多']
            , ['id' => 5,'item' => '长期不做']
            , ['id' => 6,'item' => '联系不上']
            , ['id' => 7,'item' => '挑剔']
            , ['id' => 8,'item' => '脾气不好']
            , ['id' => 9,'item' => '好沟通',]
        ],
        [
            ['id' => 10,'item' => '养宠物']
            , ['id' => 11,'item' => '有防盗门']
            , ['id' => 12,'item' => '机械锁']
            , ['id' => 13,'item' => '垃圾多']
            , ['id' => 14,'item' => '有损毁']
            , ['id' => 15,'item' => '很干净']
            , ['id' => 16,'item' => '油污重']
            , ['id' => 17,'item' => '脏乱差']
            , ['id' => 18,'item' => '位置好']
        ],
    ];

    //上门时间段
    const TIMEDATE= [
        1 => '06:00-08:00'
        , 2 => '08:00-10:00'
        , 3 => '10:00-12:00'
        , 4 => '12:00-14:00'
        , 5 => '14:00-20:00'
        , 6 => '20:00-22:00'
        , 7 => '22:00-24:00'
        , 8 => '00:00-02:00'
        , 9 => '02:00-04:00',
    ];

    //保洁区域
    const CLEANAREA = [
        1 => '个人区域'
        , 2 => '公共区域'
        , 3 => '全部区域',
    ];

    //工单无法完成备选原因
    const CONNOTREASON = [
        1 => '租户联系不上，无法开展工作'
        , 2 => '工单太多做不完'
        , 3 => '租户取消保洁'
        , 4 => '维修中无法做保洁'
        , 5 => '停水停电'
        , 6 => '本人身体不适'
        , 7 => '门锁无法打开',
    ];


    const RENTFLAG = [
        1 => '已出租',
        2 => '可出租',
        3 => '可预订',
    ];

    const WHOLERENT = [
        1 => '整租',
        2 => '合租',
    ];

    //房态类型
    const FTYPE = [
        0 => '安置房',
        1 => '民房',
        2 => '小区',
    ];
}
