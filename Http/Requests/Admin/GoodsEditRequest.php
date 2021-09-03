<?php

namespace Modules\Goods\Http\Requests\Admin;

use Modules\Goods\Http\Requests\BaseRequest;

class GoodsEditRequest extends BaseRequest
{
    /**
     * 判断用户是否有请求权限
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * 获取规则
     * @return string[]
     */
    public function newRules()
    {
        return [
            'id' => 'nullable|integer|min:1',
            'cat_ids' => 'required|string|min:1',
            'name' => 'required|string|min:1|max:100',
            'unit' => 'required|string|min:1|max:30',
            'weight' => 'required|numeric|min:0|max:99999999',
            'virtual_sales' => 'required|integer|min:0',
            'pic' => 'required|string|min:1|max:255',
            'video' => 'nullable|string|min:1|max:255',
            'imgs' => 'required|array',
            'price' => 'required|numeric|min:0.01|max:99999999',
            'original_price' => 'required|numeric|min:0.01|max:99999999',
            'cost_price' => 'nullable|numeric|min:0|max:99999999',
            'service' => 'nullable|string|min:1',
            'quota' => 'nullable|integer|min:0',
            'ucquota' => 'nullable|integer|min:0',
            'freight' => 'nullable|integer|min:0',
            'num_full' => 'nullable|integer|min:0',
            'price_full' => 'nullable|integer|min:0',
            'sort' => 'required|integer|min:0|max:100',
            "goods_num" => 'nullable|integer|min:0',
            "is_attr" => "nullable|integer|min:1|max:1",
            'attr' => 'nullable|array',
            'integral' => 'required|array',
            'share_open' => 'required|integer|min:0|max:1',
            'share_type' => 'required|integer|min:0|max:1',
            'rebate' => 'nullable|numeric|min:0',
            'share_commission_first' => 'nullable|numeric|min:0',
            'share_commission_second' => 'nullable|numeric|min:0',
            'quick_purchase' => 'required|integer|min:0|max:1',
            'hot_sale' => 'required|integer|min:0|max:1',
            'editorValue' => 'required|string|min:1',
        ];
    }

    /**
     * 获取自定义验证规则的错误消息
     * @return array
     */
    public function messages()
    {
        return [
//            'phone.regex' => "请输入正确的 :attribute",
        ];
    }

    /**
     * 获取自定义参数别名
     * @return string[]
     */
    public function attributes()
    {
        return [
            "cat_ids" => "分类",
            "name" => "商品名称",
            "unit" => "单位",
            "weight" => "重量",
            "virtual_sales" => "虚拟销售",
            "pic" => "商品缩略图",
            "video" => "商品视频",
            "imgs" => "商品图集",
            "price" => "售价",
            "original_price" => "原价",
            "cost_price" => "成本价",
            "service" => "服务内容",
            "quota" => "每次限购数量",
            "ucquota" => "单用户总限购数量",
            "freight" => "运费设置",
            "num_full" => "满件包邮",
            "price_full" => "满额包邮",
            "sort" => "排序",
            "goods_num" => "总库存",
            "is_attr" => "是否使用规格",
            "attr" => "价格和库存",
            "integral" => "积分设置",
            "share_open" => "是否开启单独分销设置",
            "share_type" => "分销佣金类型",
            "rebate" => "自购返利",
            "share_commission_first" => "一级佣金",
            "share_commission_second" => "二级佣金",
            "quick_purchase" => "是否开启快速购买",
            "hot_sale" => "是否开启热销",
            "editorValue" => "图文详情",
        ];
    }

    /**
     * 验证规则
     */
    public function check()
    {
        $validator = \Validator::make($this->all(), $this->newRules(), $this->messages(), $this->attributes());
        $error = $validator->errors()->first();
        if($error){
            return $this->resultErrorAjax($error);
        }
    }
}
