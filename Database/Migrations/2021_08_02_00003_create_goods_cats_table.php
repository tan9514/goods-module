<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateGoodsCatsTable extends Migration
{
    public $tableName = "goods_cats";

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

            $table->unsignedBigInteger('goods_id')->nullable(false)->comment("商品ID")->index("goods_id_index");
            $table->unsignedBigInteger('cat_id')->nullable(false)->comment("商品分类ID")->index("cat_id_index");
            $table->timestamps();

            // 设置外键
            $table->foreign('goods_id', $this->tableName . "_ibfk_1")->references('id')->on('goods');
            $table->foreign('cat_id', $this->tableName . "_ibfk_2")->references('id')->on('goods_cat');
        });
        $prefix = DB::getConfig('prefix');
        $qu = "ALTER TABLE " . $prefix . $this->tableName . " comment '商品分类关联表'";
        DB::statement($qu);
    }
}
