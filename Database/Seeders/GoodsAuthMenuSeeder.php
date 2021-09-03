<?php
namespace Modules\Goods\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * @author liming
 * @date 2021-07-02 10:50
 */
class GoodsAuthMenuSeeder extends Seeder
{
    public function run()
    {
        if (Schema::hasTable('auth_menu')){
            $arr = $this->defaultInfo();
            if(!empty($arr) && is_array($arr)) {
                // 删除原来已存在的菜单
                $module = config('goodsconfig.module') ?? "";
                if($module != ""){
                    DB::table('auth_menu')->where("module", $module)->delete();
                }

                $this->addInfo($arr);
            }
        }
    }

    /**
     * 遍历新增菜单
     * @param array $data
     * @param int $pid
     */
    private function addInfo(array $data, $pid = 0)
    {
        foreach ($data as $item) {
            $newPid = DB::table('auth_menu')->insertGetId([
                'pid' => $item['pid'] ?? $pid,
                'href' => $item['href'],
                'title' => $item['title'],
                'icon' => $item['icon'],
                'type' => $item['type'],
                'status' => $item['status'],
                'sort' => $item['sort'] ?? 0,
                'remark' => $item['remark'],
                'target' => $item['target'],
                'createtime' => $item['createtime'],
                'module' => $item["module"],
                'menus' => $item["menus"],
            ]);
            if($newPid <= 0) break;
            if(isset($item["contents"]) && is_array($item["contents"]) && !empty($item["contents"])) $this->addInfo($item["contents"], $newPid);
        }
    }

    /**
     * 设置后台管理菜单路由信息
     * @pid 父级
     * @href 路由
     * @title 菜单标题
     * @icon 图标
     * @type int 类型 0 顶级目录 1 目录 2 菜单 3 按钮
     * @status 状态 1 正常 2 停用
     * @remark 备注
     * @target 跳转方式
     * @createtime 创建时间
     */
    private function defaultInfo()
    {
        $module = config('goodsconfig.module') ?? "";
        $time = time();
        return [
            [
                "pid" => 10004,
                "href" => "",
                "title" => "商品管理",
                "icon" => 'fa fa-envelope',
                "type" => 1,
                "status" => 1,
                "sort" => 95,
                "remark" => "商品管理",
                "target" => "_self",
                "createtime" => $time,
                'module' => $module,
                "menus" => $module == "" ? $module : $module . "-1",
                "contents" => [
                    [   // 商品分类
                        "href" => "/admin/goods_cat/list",
                        "title" => "分类",
                        "icon" => 'fa fa-file-text-o',
                        "type" => 2,
                        "status" => 1,
                        "remark" => "分类",
                        "target" => "_self",
                        "createtime" => $time,
                        'module' => $module,
                        "menus" => $module == "" ? $module : $module . "-2",
                        "contents" => [
                            [
                                "href" => "/admin/goods_cat/list",
                                "title" => "查看分类",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "查看分类",
                                "target" => "_self",
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-3",
                            ],
                            [
                                "href" => "/admin/goods_cat/ajaxList",
                                "title" => "异步获取分类信息",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "异步获取分类信息",
                                "target" => "_self",
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-4",
                            ],
                            [
                                "href" => "/admin/goods_cat/edit",
                                "title" => "新增|编辑分类",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "新增|编辑分类",
                                "target" => "_self",
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-5",
                            ],
                            [
                                "href" => "/admin/goods_cat/del",
                                "title" => "删除分类",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "删除分类",
                                "target" => "_self",
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-6",
                            ],
                            [
                                "href" => "/admin/goods_cat/saveShow",
                                "title" => "显示|隐藏编辑",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "显示|隐藏编辑",
                                "target" => "_self",
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-7",
                            ],
                            [
                                "href" => "/admin/goods_cat/saveRecommend",
                                "title" => "推荐到首页编辑",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "推荐到首页编辑",
                                "target" => "_self",
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-8",
                            ],
                            [
                                "href" => "/admin/goods_cat/saveField",
                                "title" => "分类单字段修改",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "分类单字段修改",
                                "target" => "_self",
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-9",
                            ],
                        ]
                    ],
                    [   //  商品
                        "href" => "/admin/goods/list",
                        "title" => "商品",
                        "icon" => 'fa fa-file-text-o',
                        "type" => 2,
                        "status" => 1,
                        "remark" => "商品",
                        "target" => "_self",
                        "createtime" => $time,
                        'module' => $module,
                        "menus" => $module == "" ? $module : $module . "-10",
                        "contents" => [
                            [
                                "href" => "/admin/goods/xmSelect",
                                "title" => "异步获取附属属性字段列表",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "异步获取附属属性字段列表",
                                "target" => "_self",
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-11",
                            ],
                            [
                                "href" => "/admin/goods/list",
                                "title" => "查看商品",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "查看商品",
                                "target" => "_self",
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-12",
                            ],
                            [
                                "href" => "/admin/goods/ajaxList",
                                "title" => "异步获取商品信息",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "异步获取商品信息",
                                "target" => "_self",
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-13",
                            ],
                            [
                                "href" => "/admin/goods/edit",
                                "title" => "新增|编辑商品",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "新增|编辑商品",
                                "target" => "_self",
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-14",
                            ],
                            [
                                "href" => "/admin/goods/del",
                                "title" => "删除|批量删除",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "删除|批量删除",
                                "target" => "_self",
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-15",
                            ],
                            [
                                "href" => "/admin/goods/saveStatus",
                                "title" => "上下架|批量上下架",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "上下架|批量上下架",
                                "target" => "_self",
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-16",
                            ],
                            [
                                "href" => "/admin/goods/saveQuick",
                                "title" => "开启(关闭)|批量开启(关闭)快速购买",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "开启(关闭)|批量开启(关闭)快速购买",
                                "target" => "_self",
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-17",
                            ],
                            [
                                "href" => "/admin/goods/batchIntegral",
                                "title" => "批量设置积分",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "批量设置积分",
                                "target" => "_self",
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-18",
                            ],
                            [
                                "href" => "/admin/goods/qrcode",
                                "title" => "商品二维码",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "商品二维码",
                                "target" => "_self",
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-19",
                            ],
                            [
                                "href" => "/admin/goods/saveSort",
                                "title" => "编辑排序",
                                "icon" => 'fa fa-window-maximize',
                                "type" => 3,
                                "status" => 1,
                                "remark" => "编辑排序",
                                "target" => "_self",
                                "createtime" => $time,
                                'module' => $module,
                                "menus" => $module == "" ? $module : $module . "-20",
                            ],
                        ],
                    ],
                ]
            ],
        ];
    }
}