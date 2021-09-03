<?php
/**
 * Created By PhpStorm.
 * User: Li Ming
 * Date: 2021-08-03
 * Fun: 商品表
 */

namespace Modules\Goods\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Goods extends BaseModel
{
    use HasFactory, SoftDeletes;
    protected $table = "goods";

    /**
     * 状态
     * @return string[]
     */
    public static function getStatusArr()
    {
        return [
            "1" => "上架",
            "0" => "下架",
        ];
    }

    /**
     * 是否快速购买
     * @return string[]
     */
    public static function getQuickPurchaseArr()
    {
        return [
            "1" => "开启",
            "0" => "关闭",
        ];
    }

    /**
     * 是否加入热销
     * @return string[]
     */
    public static function getHotSaleArr()
    {
        return [
            "1" => "开启",
            "0" => "关闭",
        ];
    }

}