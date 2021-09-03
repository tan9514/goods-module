<?php

namespace Modules\Goods\Http\Requests\Admin;

use Modules\Goods\Http\Requests\BaseRequest;

class GoodsCatEditRequest extends BaseRequest
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
            'parent_id' => 'required|integer|min:0',
            'name' => 'required|string|min:1|max:100',
            'small_pic' => 'required|string|min:1|max:255',
            'large_pic' => 'nullable|string|min:1|max:255',
            'long_pic' => 'nullable|string|min:1|max:255',
            'ad_pic' => 'nullable|string|min:1|max:255',
            'ad_url' => 'nullable|url',
            'sort' => 'required|integer|min:0|max:100',
            'is_show' => 'required|integer|min:0|max:1',
            'is_recommend' => 'required|integer|min:0|max:1',
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
            'id' => '分类ID',
            'parent_id' => '父级ID',
            'name' => '分类名称',
            'small_pic' => '小图标',
            'large_pic' => '大图标',
            'long_pic' => '长图标',
            'ad_pic' => '广告图',
            'ad_url' => '广告链接',
            'sort' => '排序',
            'is_show' => '是否显示',
            'is_recommend' => '推荐到首页',
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
