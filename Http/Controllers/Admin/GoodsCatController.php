<?php
// @author liming
namespace Modules\Goods\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Modules\Goods\Http\Controllers\Controller;
use Modules\Goods\Http\Requests\Admin\GoodsCatEditFiledRequest;
use Modules\Goods\Http\Requests\Admin\GoodsCatEditRequest;
use Modules\Goods\Entities\GoodsCat;

class GoodsCatController extends Controller
{
    /**
     * 分类分页列表
     */
    public function list()
    {
        $showArr = GoodsCat::getShowArr();
        $showTest = implode("|", $showArr);
        $recommendArr = GoodsCat::getRecommendArr();
        $recommendTest = implode("|", $recommendArr);
        return view('goodsview::admin.goods_cat.list', compact('showTest', 'recommendTest'));
    }

    /**
     * ajax获取列表数据
     */
    public function ajaxList(Request $request)
    {
        $list = GoodsCat::orderBy("sort")->get();
        foreach ($list as &$item){
            $item["show_small_pic"] = $item->setPicUrl($item["small_pic"]);
            $item["show_large_pic"] = $item->setPicUrl($item["large_pic"]);
            $item["show_long_pic"] = $item->setPicUrl($item["long_pic"]);
            $item["show_ad_pic"] = $item->setPicUrl($item["ad_pic"]);
        }
        return $this->success($list);
    }

    /**
     * 新增|编辑分类信息
     * @param $id
     */
    public function edit(GoodsCatEditRequest $request)
    {
        if($request->isMethod('post')) {
            $request->check();
            $data = $request->post();
            $parent_id = $data["parent_id"] ?? 0;
            if(isset($data["id"])){
                $info = GoodsCat::where("id",$data["id"])->first();
                if(!$info) return $this->failed('数据不存在');

                if($parent_id == $info->id) return $this->failed('自己不能做为自己的上级');
            }else{
                $info = new GoodsCat();
            }

            // 处理字段内容
            $info->parent_id = $parent_id;
            if($info->parent_id > 0){
                $pInfo = GoodsCat::where("id",$info->parent_id)->first();
                if(!$pInfo) return $this->failed('上级分类不存在');
                if($pInfo->parent_id > 0) return $this->failed('上级分类不是顶级分类');
            }

            $info->name = $data["name"];
            $info->small_pic = $data["small_pic"];
            if($info->small_pic != "" && !file_exists($info->small_pic)){
                return $this->failed('上传的小图标不存在');
            }
            $info->large_pic = $data["large_pic"] ?? "";
            if($info->large_pic != "" && !file_exists($info->large_pic)){
                return $this->failed('上传的大图标不存在');
            }
            $info->long_pic = $data["long_pic"] ?? "";
            if($info->long_pic != "" && !file_exists($info->long_pic)){
                return $this->failed('上传的长图标不存在');
            }
            $info->ad_pic = $data["ad_pic"] ?? "";
            if($info->ad_pic != "" && !file_exists($info->ad_pic)){
                return $this->failed('上传的广告图不存在');
            }
            $info->ad_url = $data["ad_url"] ?? "";
            $info->sort = $data["sort"];

            $info->is_show = $data["is_show"];
            $showArr = $info->getShowArr();
            if(!isset($showArr[$info->is_show])) return $this->failed('显示值不存在');

            $info->is_recommend = $data["is_recommend"];
            $isRecommendArr = $info->getRecommendArr();
            if(!isset($isRecommendArr[$info->is_recommend])) return $this->failed('推荐值不存在');
            try {
                if(!$info->save()) return $this->failed("操作失败");
                return $this->success();
            }catch (\Exception $e){
                return $this->failed($e->getMessage());
            }
        } else {
            $id = $request->input('id') ?? 0;
            if($id > 0){
                $catArr = GoodsCat::where([
                    ["parent_id", "=", "0"],
                    ["id", "!=", $id]
                ])->select("id","name")->get();
                $info = GoodsCat::where('id',$id)->first();
                if(!$info) return $this->failed('数据不存在');
                $info["show_small_pic"] = $info->setPicUrl($info["small_pic"]);
                $info["show_large_pic"] = $info->setPicUrl($info["large_pic"]);
                $info["show_long_pic"] = $info->setPicUrl($info["long_pic"]);
                $info["show_ad_pic"] = $info->setPicUrl($info["ad_pic"]);
                if($info->parent_id == 0){
                    $zInfos = GoodsCat::where('parent_id',$info->id)->first();
                    if($zInfos) $catArr = (object)[];
                }
                $title = "编辑分类";
            }else{
                $catArr = GoodsCat::where([
                    ["parent_id", "=", "0"]
                ])->select("id","name")->get();
                $info = new GoodsCat();
                $title = "新增分类";
            }
            return view('goodsview::admin.goods_cat.edit', compact('info', 'title', 'catArr'));
        }
    }

    /**
     * 删除分类
     */
    public function del(Request $request)
    {
        if($request->isMethod('post')){
            $id = $request->input('id');

            $info = GoodsCat::where('id',$id)->first();
            if(!$info) return $this->failed("数据不存在");

            try {
                $catArr = GoodsCat::where("parent_id", $id)->pluck("id")->toArray();
                $catArr[] = $id;
                if(!empty($catArr)){
                    if(GoodsCat::whereIn("id", $catArr)->delete()) return $this->success();
                }
                return $this->failed('操作失败');
            }catch (\Exception $e){
                $msg = $e->getMessage();
                $count = substr_count($msg, "1451 Cannot delete");
                if($count > 0){
                    $msg = "已有关联的商品，不能删除分类";
                }
                return $this->failed($msg);
            }
        }
        return $this->failed('请求出错.');
    }

    /**
     * 显示：显示 隐藏功能
     */
    public function saveShow(Request $request)
    {
        if($request->isMethod('post')){
            $id = $request->input('id');
            $is_show = $request->input("is_show");
            if($is_show === "true"){
                $is_show = 1;
            }else if($is_show === "false"){
                $is_show = 0;
            }
            $isShowArr = GoodsCat::getShowArr();
            if(!isset($isShowArr[$is_show])) return $this->failed('显示值不存在');

            $info = GoodsCat::where('id',$id)->first();
            if(!$info) return $this->failed("数据不存在");
            $info->is_show = $is_show;

            try {
                if($info->save()) return $this->success();
                return $this->failed('操作失败');
            }catch (\Exception $e){
                return $this->failed($e->getMessage());
            }
        }
        return $this->failed('请求出错.');
    }

    /**
     * 推荐：开启 关闭功能
     */
    public function saveRecommend(Request $request)
    {
        if($request->isMethod('post')){
            $id = $request->input('id');
            $is_recommend = $request->input("is_recommend");
            if($is_recommend === "true"){
                $is_recommend = 1;
            }else if($is_recommend === "false"){
                $is_recommend = 0;
            }
            $isRecommendArr = GoodsCat::getRecommendArr();
            if(!isset($isRecommendArr[$is_recommend])) return $this->failed('推荐值不存在');
            $info = GoodsCat::where('id',$id)->first();
            if(!$info) return $this->failed("数据不存在");
            $info->is_recommend = $is_recommend;

            try {
                if($info->save()) return $this->success();
                return $this->failed('操作失败');
            }catch (\Exception $e){
                return $this->failed($e->getMessage());
            }
        }
        return $this->failed('请求出错.');
    }

    /**
     * 分类单字段修改
     */
    public function saveField(GoodsCatEditFiledRequest $request)
    {
        if($request->isMethod('post')){
            $request->check();
            $id = $request->input('id');
            $info = GoodsCat::where('id',$id)->first();
            if(!$info) return $this->failed("数据不存在");

            $value = $request->input("value");
            $field = $request->input("field");
            switch ($field){
                case "sort": // 排序
                    if($value < 0 || $value > 100) return $this->failed('排序值只能为大于等于0 ~ 小于等于100');
                    $info->sort = $value;
                    break;
                default:
                    return $this->failed('支持的单字段不包含当前字段');
            }

            try {
                if($info->save()) return $this->success();
                return $this->failed('操作失败');
            }catch (\Exception $e){
                return $this->failed($e->getMessage());
            }
        }
        return $this->failed('请求出错.');
    }
}
