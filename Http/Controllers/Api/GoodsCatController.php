<?php

namespace Modules\Goods\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Goods\Http\Controllers\Controller;
use Modules\Goods\Http\Requests\Api\GoodsCatListRequest;
use Modules\Goods\Entities\GoodsCat;

class GoodsCatController extends Controller
{
    /**
     * 获取分类列表
     */
    public function list(GoodsCatListRequest $request)
    {
        $request->check();
        $parent_id = $request->input("parent_id") ?? "";
        $is_recommend = $request->input("is_recommend") ?? "";

        $where = [
            ["is_show", "=", "1"]
        ];
        if($is_recommend == "1") $where[] = ["is_recommend", "=", $is_recommend];

        $list = (object)[];
        if($parent_id == ""){
            // 获取全部分类
            $list = GoodsCat::where($where)->orderBy("sort")->get();
        }else if($parent_id == "0"){
            // 获取所有一级分类
            $where[] = ["parent_id", "=", "0"];
            $list = GoodsCat::where($where)->orderBy("sort")->get();
        }else if($parent_id > 0){
            // 获取子分类
            $where[] = ["parent_id", "=", $parent_id];
            $list = GoodsCat::where($where)->orderBy("sort")->get();
        }
        foreach ($list as &$item){
            $item["show_small_pic"] = $item->setPicUrl($item["small_pic"]);
            $item["show_large_pic"] = $item->setPicUrl($item["large_pic"]);
            $item["show_long_pic"] = $item->setPicUrl($item["long_pic"]);
            $item["show_ad_pic"] = $item->setPicUrl($item["ad_pic"]);
        }

        return $this->success($list,"获取成功");
    }
}
