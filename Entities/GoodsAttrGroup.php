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

class GoodsAttrGroup extends BaseModel
{
    use HasFactory;
    protected $table = "goods_attr_group";
}