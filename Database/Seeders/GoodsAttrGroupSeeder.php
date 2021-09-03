<?php
namespace Modules\Goods\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * @author liming
 * @date 2021-08-12
 */
class GoodsAttrGroupSeeder extends Seeder
{
    public function run()
    {
        if (Schema::hasTable('goods_attr_group')){
            $info = DB::table('goods_attr_group')->where('id', '>', 0)->first();
            if(!$info){
                $arr = $this->defaultInfo();
                if(!empty($arr) && is_array($arr)) {
                    $created_at = $updated_at = date("Y-m-d H:i:s");
                    foreach ($arr as $name => $item) {
                        DB::table('goods_attr_group')->insert([
                            'name' => $item['name'],
                            'created_at' => $created_at,
                            'updated_at' => $updated_at,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * 新增初始规格组信息
     */
    private function defaultInfo()
    {
        return [
            ["name" => "规格"],
        ];
    }
}