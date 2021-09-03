<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateGoodsAttrTable extends Migration
{
    public $tableName = "goods_attr";

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
            $table->string("name")->nullable(false)->comment("规格名称")->index("index_name");
            $table->unsignedBigInteger('goods_attr_group_id')->nullable(false)->comment("商品规格组ID");
            $table->timestamps();

            // 设置外键
            $table->foreign('goods_attr_group_id', $this->tableName . "_ibfk_1")->references('id')->on('goods_attr_group');
        });
        $prefix = DB::getConfig('prefix');
        $qu = "ALTER TABLE " . $prefix . $this->tableName . " comment '商品规格表'";
        DB::statement($qu);
    }
}
