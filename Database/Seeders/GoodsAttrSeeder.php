<?php
namespace Modules\Goods\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * @author liming
 * @date 2021-08-12
 */
class GoodsAttrSeeder extends Seeder
{
    public function run()
    {
        if (Schema::hasTable('goods_attr')){
            $info = DB::table('goods_attr')->where('id', '>', 0)->first();
            if(!$info){
                $arr = $this->defaultInfo();
                if(!empty($arr) && is_array($arr)) {
                    $created_at = $updated_at = date("Y-m-d H:i:s");
                    foreach ($arr as $name => $item) {
                        DB::table('goods_attr')->insert([
                            'name' => $item['name'],
                            'goods_attr_group_id' => 1,
                            'created_at' => $created_at,
                            'updated_at' => $updated_at,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * 新增初始规格信息
     */
    private function defaultInfo()
    {
        return [
            ["name" => "默认"],
        ];
    }
}