<?php
/**
 * Created By PhpStorm.
 * User: Li Ming
 * Date: 2021-08-03
 * Fun: 商品分类关联表
 */

namespace Modules\Goods\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class GoodsCats extends BaseModel
{
    use HasFactory;
    protected $table = "goods_cats";

//
//    public static function getStatusArr()
//    {
//        return [
//            "1" => "等待发送",
//            "2" => "发送成功",
//            "3" => "发送失败",
//        ];
//    }
//
//    /**
//     * 获取短信状态
//     * @return string
//     */
//    public function getStatusNameAttribute()
//    {
//        return self::getStatusArr()[$this->status] ?? "";
//    }
}