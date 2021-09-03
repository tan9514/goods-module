<?php
/**
 * Created By PhpStorm.
 * User: Li Ming
 * Date: 2021-08-03
 * Fun: 商品分类表
 */

namespace Modules\Goods\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class GoodsCat extends BaseModel
{
    use HasFactory, SoftDeletes;
    protected $table = "goods_cat";

    /**
     * 是否显示
     * @return string[]
     */
    public static function getShowArr()
    {
        return [
            "1" => "显示",
            "0" => "隐藏",
        ];
    }

    /**
     * 是否推荐到首页
     * @return string[]
     */
    public static function getRecommendArr()
    {
        return [
            "1" => "推荐",
            "0" => "取消",
        ];
    }
}