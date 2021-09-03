<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateGoodsCatTable extends Migration
{
    public $tableName = "goods_cat";

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
            $table->unsignedBigInteger("parent_id")->nullable(false)->default("0")->comment("分类父级ID");
            $table->string('name', 100)->nullable(false)->default("")->comment("分类名称")->index("name_index");
            $table->string("small_pic", 255)->nullable(false)->comment("分类小图标");
            $table->string("large_pic", 255)->nullable(false)->default("")->comment("分类大图标");
            $table->string("long_pic", 255)->nullable(false)->default("")->comment("分类长图标");
            $table->string("ad_pic", 255)->nullable(false)->default("")->comment("分类广告图");
            $table->string("ad_url", 255)->nullable(false)->default("")->comment("分类广告链接地址");
            $table->unsignedTinyInteger("sort")->nullable(false)->default(100)->comment("排序: 升序");
            $table->tinyInteger("is_show")->nullable(false)->default(1)->comment("是否显示：0=隐藏，1=显示");
            $table->tinyInteger("is_recommend")->nullable(false)->default(0)->comment("是否推荐到首页：0=否，1=是");
            $table->timestamps();
            $table->softDeletes();
        });
        $prefix = DB::getConfig('prefix');
        $qu = "ALTER TABLE " . $prefix . $this->tableName . " comment '商品分类表'";
        DB::statement($qu);
    }
}
