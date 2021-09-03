<?php

namespace Modules\Goods\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class GoodsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call([
            GoodsAuthMenuSeeder::class,
            GoodsAttrGroupSeeder::class,
            GoodsAttrSeeder::class
        ]);
    }
}
