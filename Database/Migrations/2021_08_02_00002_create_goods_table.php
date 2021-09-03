<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateGoodsTable extends Migration
{
    public $tableName = "goods";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable($this->tableName)) $this->create();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }

    /**
     * 执行创建表
     */
    private function create()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';      // 设置存储引擎
            $table->charset = 'utf8';       // 设置字符集
            $table->collation  = 'utf8_general_ci';       // 设置排序规则

            $table->id();
            $table->string("name", 100)->nullable(false)->comment("商品名称")->index("name_index");
            $table->string('unit', 30)->nullable(false)->default("件")->comment("商品单位")->index("unit_index");
            $table->decimal("weight", 10, 2)->nullable(false)->default("0")->comment("商品重量");
            $table->unsignedInteger("virtual_sales")->nullable(false)->default("0")->comment("虚拟销量");
            $table->string('pic')->nullable(false)->comment("商品封面图");
            $table->string('video')->default("")->comment("商品视频");
            $table->longText("imgs")->nullable(false)->comment("商品图片集");
            $table->decimal('price', 10, 2)->nullable(false)->default("1")->comment("商品售价");
            $table->decimal('original_price', 10, 2)->nullable(false)->default("1")->comment("商品原价");
            $table->decimal('cost_price', 10, 2)->nullable(false)->default("0.00")->comment("商品成本价");
            $table->longText("service")->comment("服务内容");
            $table->unsignedInteger("quota")->nullable(false)->default("0")->comment("每次下单限购数量");
            $table->unsignedInteger("ucquota")->nullable(false)->default("0")->comment("用户总限购数量");
            $table->unsignedInteger("freight")->nullable(false)->default("0")->comment("运费模板ID");
            $table->unsignedInteger("num_full")->nullable(false)->default("0")->comment("单品满件包邮");
            $table->unsignedInteger("price_full")->nullable(false)->default("0")->comment("单品满额包邮");
            $table->unsignedTinyInteger("sort")->nullable(false)->default(100)->comment("排序: 升序");
            $table->unsignedInteger("goods_num")->nullable(false)->default("0")->comment("商品总库存");
            $table->longText("attr")->nullable(false)->comment("规格的库存,库存及价格");
            $table->longText("integral")->nullable(false)->comment("积分设置");
            $table->tinyInteger("share_open")->nullable(false)->default(0)->comment("单独分销设置：0=关闭，1=开启");
            $table->tinyInteger("share_type")->nullable(false)->default(0)->comment("分销佣金类型：0=百分比，1=固定金额");
            $table->decimal("rebate", 10, 2)->nullable(false)->default("0")->comment("自购返利");
            $table->decimal("share_commission_first", 10, 2)->nullable(false)->default("0")->comment("一级佣金");
            $table->decimal("share_commission_second", 10, 2)->nullable(false)->default("0")->comment("二级佣金");
            $table->tinyInteger("quick_purchase")->nullable(false)->default(0)->comment("是否快速购买：0=关闭   1=开启");
            $table->longText("detail")->nullable(false)->comment("图文详情");
            $table->tinyInteger("status")->nullable(false)->default(0)->comment("上架状态：0=下架，1=上架");
            $table->tinyInteger("hot_sale")->nullable(false)->default(0)->comment("是否加入热销：0=关闭   1=开启");

            $table->timestamps();
            $table->softDeletes();
        });
        $prefix = DB::getConfig('prefix');
        $qu = "ALTER TABLE " . $prefix . $this->tableName . " comment '商品表'";
        DB::statement($qu);
    }
}
