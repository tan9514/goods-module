<?php
// @author liming
namespace Modules\Goods\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Goods\Http\Controllers\Controller;
use Modules\Goods\Http\Requests\Admin\GoodsEditFiledRequest;
use Modules\Goods\Http\Requests\Admin\GoodsEditRequest;
use Modules\Goods\Entities\Goods;
use Modules\Goods\Entities\GoodsAttr;
use Modules\Goods\Entities\GoodsAttrGroup;
use Modules\Goods\Entities\GoodsCat;
use Modules\Goods\Entities\GoodsCats;

class GoodsController extends Controller
{
    /**
     * ajax 获取所有分类
     * @param Request $request
     * @return mixed
     * @author tan bing
     * @date 2021-06-17 16:56
     */
    public function xmSelect(Request $request)
    {
        $goods_id = $request->input("id") ?? 0;
        $catIdArr = [];
        if($goods_id > 0) {
            $catIdArr = GoodsCats::where("goods_id", $goods_id)->pluck("cat_id")->toArray();
        }

        $goodsCat = GoodsCat::where([
            ["parent_id", "=", 0]
        ])->orderBy("sort")->select('id as value','name')->get()->toArray();
        foreach ($goodsCat as &$item){
            if(in_array($item["value"], $catIdArr)) $item['selected'] = true;
            $item["children"] = GoodsCat::where([
                ["parent_id", "=", $item["value"]]
            ])->orderBy("sort")->select('id as value','name')->get()->toArray();
            foreach ($item["children"] as &$chItem){
                if(in_array($chItem["value"], $catIdArr)) $chItem['selected'] = true;
            }
        }
        return $this->success(compact("goodsCat", "goods_id", "catIdArr"));
    }
    
    /**
     * 分页列表
     */
    public function list()
    {
        $catList = GoodsCat::select("id","name")->orderBy("sort")->get();
        $statusArr = Goods::getStatusArr();
        $statusTest = implode("|", $statusArr);
        $quickArr = Goods::getQuickPurchaseArr();
        $quickTest = implode("|", $quickArr);
        return view('goodsview::admin.goods.list',compact('catList', 'statusArr', 'statusTest', 'quickArr', 'quickTest'));
    }

    /**
     * ajax获取列表数据
     */
    public function ajaxList(Request $request)
    {
        $pagesize = $request->input('limit'); // 每页条数
        $page = $request->input('page',1);//当前页
        $where = [];

        $status = $request->input('status');
        if($status != "") $where[] = ["goods.status", "=", $status];
        $quick_purchase = $request->input("quick_purchase");
        if($quick_purchase != "") $where[] = ["goods.quick_purchase", "=", $quick_purchase];
        $name = $request->input('name');
        if($name != "") $where[] = ["goods.name", "like", "%{$name}%"];
        $cat_ids = $request->input("cat_ids");
        $catIdArr = [];
        if($cat_ids != "") $catIdArr = explode(",",$cat_ids);

        //获取总条数
        $countModel = Goods::leftJoin('goods_cats as gcs','goods.id','=','gcs.goods_id')->where($where);
        if(!empty($catIdArr)) $countModel->whereIn("gcs.cat_id", $catIdArr);
        $count = $countModel->distinct("goods.id")->count();

        //求偏移量
        $offset = ($page-1)*$pagesize;
        $listModel = Goods::leftJoin('goods_cats as gcs','goods.id','=','gcs.goods_id')->where($where);
        if(!empty($catIdArr)) $listModel->whereIn("gcs.cat_id", $catIdArr);
        $list = $listModel->offset($offset)
            ->limit($pagesize)
            ->orderBy("goods.sort")->orderBy("goods.id", "desc")
            ->distinct("goods.id")
            ->select("goods.*")
            ->get();
        foreach ($list as &$item){
            $item["show_pic"] = $item->setPicUrl($item->pic);
            $item['catArr'] = GoodsCats::join('goods_cat as gc','goods_cats.cat_id','=','gc.id')
                ->where("goods_cats.goods_id", $item->id)->pluck("name");
        }
        return $this->success(compact('list', 'count'));
    }

    /**
     * 新增|编辑商品信息
     * @param $id
     */
    public function edit(GoodsEditRequest $request)
    {
        if($request->isMethod('post')) {
            $request->check();
            $data = $request->post();

            if(isset($data["id"])){
                $info = Goods::where("id",$data["id"])->first();
                if(!$info) return $this->failed('数据不存在');
            }else{
                $info = new Goods();
            }

            $info->name = $data["name"];
            $info->unit = $data["unit"];
            $info->weight = $data["weight"];
            $info->virtual_sales = $data["virtual_sales"];
            $info->pic = $data["pic"];
            if(!file_exists($info->pic)) return $this->failed('上传的商品缩略图不存在');
            $info->video = $data["video"] ?? "";
            if($info->video != "" && !file_exists($info->video)) return $this->failed('上传的商品视频不存在');
            $info->imgs = $data["imgs"];
            foreach ($info->imgs as $img){
                if(!file_exists($info->pic)) return $this->failed('上传的商品缩略图不存在');
            }
            $info->imgs = json_encode($info->imgs, JSON_UNESCAPED_UNICODE);
            $info->price = $data["price"];
            $info->original_price = $data["original_price"];
            $info->cost_price = $data["cost_price"] ?? 0;
            $info->service = $data["service"] ?? "";
            $info->quota = $data["quota"] ?? 0;
            $info->ucquota = $data["ucquota"] ?? 0;
            $info->freight = $data["freight"] ?? 0;
            $info->num_full = $data["num_full"] ?? 0;
            $info->price_full = $data["price_full"] ?? 0;
            $info->sort = $data["sort"];
            $info->quick_purchase = $data["quick_purchase"];
            $info->hot_sale = $data["hot_sale"];
            $info->detail = $data["editorValue"];
            // 积分设置
            $integral = $data["integral"];
            $integral["more"] = $integral["more"] ?? 0;
            $info->integral = json_encode($integral, JSON_UNESCAPED_UNICODE);
            // 分销设置
            $info->share_open = $data["share_open"];
            $info->share_type = $data["share_type"];
            $info->rebate = $data["rebate"] ?? 0;
            $info->share_commission_first = $data["share_commission_first"] ?? 0;
            $info->share_commission_second = $data["share_commission_second"] ?? 0;

            DB::beginTransaction();
            try {
                // 规格
                $is_attr = $data["is_attr"] ?? 0;
                if($is_attr == 1){ // 使用规格
                    $info->goods_num = 0;
                    $attr = $data["attr"] ?? [];
                    foreach ($attr as &$attrInfo){
                        if(!isset($attrInfo["attr_list"]) || !is_array($attrInfo["attr_list"])) throw new \Exception("规格库存参数错误");
                        foreach ($attrInfo["attr_list"] as &$attrListInfo){
                            $attrListInfo["attr_name"] = $attrListInfo["attr_name"] ?? "";
                            $attrListInfo["group_name"] = $attrListInfo["group_name"] ?? "";
                            if($attrListInfo["attr_name"] == "" || $attrListInfo["group_name"] == "") throw new \Exception("规格库存参数错误");
                            $attrGroupInfo = GoodsAttrGroup::where("name", $attrListInfo["group_name"])->first();
                            if(!$attrGroupInfo){
                                $GoodsAttrGroupModel = new GoodsAttrGroup();
                                $GoodsAttrGroupModel->name = $attrListInfo["group_name"];
                                if(!$GoodsAttrGroupModel->save()) throw new \Exception("操作失败: 新增规格组信息失败");
                                $attrListInfo["group_id"] = $GoodsAttrGroupModel->id;
                            }else{
                                $attrListInfo["group_id"] = $attrGroupInfo->id;
                            }
                            $attrIn = GoodsAttr::where("name", $attrListInfo["attr_name"])->first();
                            if(!$attrIn){
                                $GoodsAttrModel = new GoodsAttr();
                                $GoodsAttrModel->name = $attrListInfo["attr_name"];
                                $GoodsAttrModel->goods_attr_group_id = $attrListInfo["group_id"];
                                if(!$GoodsAttrModel->save()) throw new \Exception("操作失败: 新增规格信息失败");
                                $attrListInfo["attr_id"] = $GoodsAttrModel->id;
                            }else{
                                $attrListInfo["attr_id"] = $attrIn->id;
                            }
                        }

                        $attrInfo["no"] = $attrInfo["no"] ?? "";
                        $attrInfo["num"] = (int)doubleval($attrInfo["num"] ?? 0);
                        $attrInfo["price"] = doubleval($attrInfo["price"] ?? 0);
                        $attrInfo["pic"] = $attrInfo["pic"] ?? "";
                        if($attrInfo["pic"] != "" && !file_exists($attrInfo["pic"])) throw new \Exception('上传的规格图片不存在');
                        $info->goods_num += $attrInfo["num"];
                    }
                }else{ // 不使用规格
                    $info->goods_num = $data["goods_num"] ?? 0;
                    $attr = [];
                    $attr[] = [
                        "attr_list" => [
                            ["group_id" => "1", "group_name" => "规格", "attr_id" => "1", "attr_name" => "默认"]
                        ],
                        "no" => "",
                        "num" => doubleval($info->goods_num),
                        "price" => 0,
                        "pic" => 0,
                    ];
                }
                $info->attr = json_encode($attr, JSON_UNESCAPED_UNICODE);
                if(!$info->save()) throw new \Exception("操作失败");

                // 关联分类
                GoodsCats::where("goods_id", $info->id)->delete();
                if($data["cat_ids"] != ""){
                    $catInfos = GoodsCat::whereIn("id", explode(",", $data["cat_ids"]))->get();
                    foreach ($catInfos as $catInfo){
                        $GoodsCatsModel = new GoodsCats();
                        $GoodsCatsModel->goods_id = $info->id;
                        $GoodsCatsModel->cat_id = $catInfo->id;
                        if(!$GoodsCatsModel->save()) throw new \Exception("操作失败: 新增关联分类信息失败");
                    }
                }

                DB::commit();
                return $this->success();
            }catch (\Exception $e){
                DB::rollBack();
                return $this->failed($e->getMessage());
            }
        } else {
            $id = $request->input('id') ?? 0;
            $groupList = [];
            if($id > 0){
                $info = Goods::where('id',$id)->first();
                $title = "编辑商品";
                $info["show_pic"] = $info->setPicUrl($info["pic"]);
                $info["show_video"] = $info->setPicUrl($info["video"]);
                $info->imgs = json_decode($info->imgs, true);
                $info->integral = json_decode($info->integral, true);
                $info->attr = json_decode($info->attr, true);
                $goodsNum = 0;
                foreach ($info->attr as $ai){
                    foreach ($ai["attr_list"] as $ii){
                        if($ii["group_id"] != 1){
                            if(!isset($groupList[$ii["group_id"]])){
                                $groupList[$ii["group_id"]]["name"] = $ii["group_name"];
                            }
                        }
                        if($ii["attr_id"] != 1){
                            if(!isset($groupList[$ii["group_id"]]["attr_list"][$ii["attr_id"]])){
                                $groupList[$ii["group_id"]]["attr_list"][$ii["attr_id"]] = $ii["attr_name"];
                            }
                        }
                    }
                    $goodsNum += $ai["num"];
                }
                if(!empty($groupList)) $goodsNum = 0;
//                echo "<pre>";
//                var_dump($info->attr);
//                exit();

                $info->weight = doubleval($info->weight);
                $info->price = doubleval($info->price);
                $info->original_price = doubleval($info->original_price);
                $info->cost_price = doubleval($info->cost_price);
            }else{
                $info = new Goods();
                $title = "新增商品";
                $goodsNum = 0;
            }
            $domain = $info->getDomain();
            return view('goodsview::admin.goods.edit', compact('info', 'title', 'domain', 'groupList', 'goodsNum'));
        }
    }

    /**
     * 删除商品
     */
    public function del(Request $request)
    {
        if($request->isMethod('post')){
            $id = $request->input('id');
            if(is_array($id)) {
                // 数组删除
                try {
                    if(Goods::whereIn("id", $id)->delete()) return $this->success();
                    throw new \Exception("操作失败");
                } catch (\Exception $e) {
                    return $this->failed($e->getMessage());
                }
            } else {
                $info = Goods::where('id', $id)->first();
                if (!$info) return $this->failed("数据不存在");
                try {
                    if (!$info->delete()) throw new \Exception("操作失败");
                    return $this->success();
                } catch (\Exception $e) {
                    return $this->failed($e->getMessage());
                }
            }
        }
        return $this->failed('请求出错.');
    }

    /**
     * 状态：上架 下架功能
     */
    public function saveStatus(Request $request)
    {
        if($request->isMethod('post')){
            $id = $request->input('id');
            $status = $request->input("status");
            if($status === "true"){
                $status = 1;
            }else if($status === "false"){
                $status = 0;
            }
            $statusArr = Goods::getStatusArr();
            if(!isset($statusArr[$status])) return $this->failed('状态值不存在');

            if(is_array($id)){
                // 批量上下架
                $goodsList = Goods::whereIn("id", $id)->select("id","name","goods_num","status")->get();
                DB::beginTransaction();
                try {
                    foreach ($goodsList as $goodsInfo){
                        if($goodsInfo->goods_num <= 0 && $status == 1) throw new \Exception('商品【'.$goodsInfo->name.'】库存不足，请先完善商品库存');
                        $goodsInfo->status = $status;
                        if (!$goodsInfo->save()) throw new \Exception("商品【".$goodsInfo->name."】操作失败");
                    }
                    DB::commit();
                    return $this->success();
                }catch (\Exception $e){
                    DB::rollBack();
                    return $this->failed($e->getMessage());
                }
            }else{
                $info = Goods::where('id',$id)->first();
                if(!$info) return $this->failed("数据不存在");
                if($info->goods_num <= 0 && $status == 1) return $this->failed('商品【'.$info->name.'】库存不足，请先完善商品库存');
                $info->status = $status;
                try {
                    if (!$info->save()) throw new \Exception("商品【".$info->name."】操作失败");
                    return $this->success();
                } catch (\Exception $e) {
                    return $this->failed($e->getMessage());
                }
            }
        }
        return $this->failed('请求出错.');
    }

    /**
     * 快速购买：开启 关闭功能
     */
    public function saveQuick(Request $request)
    {
        if($request->isMethod('post')){
            $id = $request->input('id');
            $quick_purchase = $request->input("quick_purchase");
            if($quick_purchase === "true"){
                $quick_purchase = 1;
            }else if($quick_purchase === "false"){
                $quick_purchase = 0;
            }
            $quickArr = Goods::getQuickPurchaseArr();
            if(!isset($quickArr[$quick_purchase])) return $this->failed('快速购买值不存在');

            if(is_array($id)){
                // 批量快速购买
                if(!Goods::whereIn("id", $id)->update(["quick_purchase" => $quick_purchase])) return $this->failed("批量设置快速购买失败");
                return $this->success();
            }else{
                $info = Goods::where('id',$id)->first();
                if(!$info) return $this->failed("数据不存在");
                $info->quick_purchase = $quick_purchase;
                try {
                    if (!$info->save()) throw new \Exception("快速购买操作失败");
                    return $this->success();
                } catch (\Exception $e) {
                    return $this->failed($e->getMessage());
                }
            }
        }
        return $this->failed('请求出错.');
    }

    /**
     * 批量设置积分功能
     * @param Request $request
     * @return mixed
     */
    public function batchIntegral(Request $request)
    {
        if($request->isMethod('post')){
            $ids = $request->post("ids") ?? "";
            if($ids == "") return $this->failed('数据不存在');
            $idsArr = explode(",", $ids);

            $integral = $request->post("integral") ?? [];
            if(empty($integral)) return $this->failed('积分参数不存在');
            $integral["more"] = $integral["more"] ?? 0;
            $integral = json_encode($integral, JSON_UNESCAPED_UNICODE);

            if(!Goods::whereIn("id", $idsArr)->update(["integral" => $integral])) return $this->failed("设置积分失败");
            return $this->success();
        }else{
            $id = $request->input('ids') ?? "";
            if($id == "") exit('
                    <div style="position: absolute; left: 50%;top: 50%;transform: translate(-50%, -50%);text-align: center;">
                        <img style="max-height: 265px;vertical-align: middle;" src="/layuimini/images/ic_404.png">
                        <div style="display: inline-block;text-align: center;vertical-align: middle;padding-left: 30px;">
                            <h1 style="color: #434e59;font-size: 72px;font-weight: 600;margin-bottom: 10px;"> </h1>
                            <div style="color: #777;font-size: 20px;line-height: 28px;margin-bottom: 16px;">请勾选要修改的数据！</div>
                        </div>
                    </div>
                ');

            $idArr = explode(",", $id);
            $infos = Goods::whereIn("id", $idArr)->pluck("name")->toArray();
            if(count($infos) <= 0) exit('
                    <div style="position: absolute; left: 50%;top: 50%;transform: translate(-50%, -50%);text-align: center;">
                        <img style="max-height: 265px;vertical-align: middle;" src="/layuimini/images/ic_404.png">
                        <div style="display: inline-block;text-align: center;vertical-align: middle;padding-left: 30px;">
                            <h1 style="color: #434e59;font-size: 72px;font-weight: 600;margin-bottom: 10px;"> </h1>
                            <div style="color: #777;font-size: 20px;line-height: 28px;margin-bottom: 16px;">请勾选要修改的数据！</div>
                        </div>
                    </div>
                ');

            $title = "批量设置积分";
            return view('goodsview::admin.goods.batch_integral', compact('title', 'id', 'infos'));
        }
    }

    /**
     * 修改排序
     */
    public function saveSort(GoodsEditFiledRequest $request)
    {
        if($request->isMethod('post')){
            $request->check();
            $id = $request->input('id');
            $info = Goods::where('id',$id)->first();
            if(!$info) return $this->failed("数据不存在");

            $value = $request->input("value");
            $field = $request->input("field");
            switch ($field){
                case "sort": // 排序
                    $value = doubleval($value);
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

    /**
     * 获取商品小程序二维码
     * @param Request $request
     */
    public function qrcode(Request $request)
    {

    }
}
