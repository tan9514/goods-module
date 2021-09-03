@extends('admin.public.header')
@section('title',$title)
@section('listcontent')
    <form class="layui-form">
    <div class="layui-form layuimini-form">
        <div class="layui-form-item">
            <label class="layui-form-label">勾选的商品</label>
            <div class="layui-input-block">
                @foreach($infos as $it)
                <input type="text" value="{{$it ?? ''}}" class="layui-input" style="margin-bottom: 5px;" disabled />
                @endforeach
            </div>
        </div>

        <input type="hidden" name="ids" value="{{$id}}" />

        <div class="layui-form-item ">
            <label class="layui-form-label">积分赠送</label>
            <div class="layui-input-block">
                <input type="number" name="integral[give]" min="0" placeholder="请输入积分赠送" value="0" class="layui-input" style="display: inline; width: 50%; position: absolute" />
                <span style="position: absolute; right: 50%; display: block; line-height: 36px; color: #464a4c; padding: 0 13px; border: 1px solid #e6e6e6; background-color: #eceeef; border-radius: 2px;">分</span>
                <div style="font-size: 10px; color: #636c72; padding-top: 40px;">
                    <p style="line-height: normal">会员购物赠送的积分, 如果不填写或填写0，则默认为不赠送积分，如果带%则为按成交价格的比例计算积分</p>
                    <p style="line-height: normal">如: 购买2件，设置10 积分, 不管成交价格是多少， 则购买后获得20积分</p>
                    <p style="line-height: normal">如: 购买2件，设置10%积分, 成交价格2 * 200= 400， 则购买后获得 40 积分（400*10%）</p>
                </div>
            </div>
        </div>

        <div class="layui-form-item ">
            <label class="layui-form-label">积分抵扣</label>
            <div class="layui-input-block">
                <span style="position: absolute; display: block; line-height: 36px; color: #464a4c; padding: 0 13px; border: 1px solid #e6e6e6; background-color: #eceeef; border-radius: 2px; z-index: 999">最多抵扣</span>
                <input type="number" name="integral[forehead]" min="0" placeholder="请输入积分抵扣" value="0" class="layui-input" style="display: inline; width: 50%; position: absolute; padding-left: 90px;" />
                <span style="position: absolute; right: 50%; display: block; line-height: 36px; color: #464a4c; padding: 0 13px; border: 1px solid #e6e6e6; background-color: #eceeef; border-radius: 2px;">元</span>
                <div style="padding-top: 40px;">
                    <input type="checkbox" name="integral[more]" title="允许多件累计折扣" value="1" />
                    <div style="font-size: 10px; color: #636c72;">如果设置0，则不支持积分抵扣 如果带%则为按成交价格的比例计算抵扣多少元</div>
                </div>
            </div>
        </div>

        <div class="hr-line"></div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-normal" id="saveBtn" lay-submit lay-filter="saveBtn">保存</button>
            </div>
        </div>

    </div>
    </form>
@endsection

@section('listscript')
    <script type="text/javascript">
        layui.use(['iconPickerFa', 'form', 'layer', 'upload', 'xmSelect'], function () {
            var iconPickerFa = layui.iconPickerFa,
                form = layui.form,
                layer = layui.layer,
                upload = layui.upload,
                xmSelect = layui.xmSelect,
                $ = layui.$;

            //监听提交
            form.on('submit(saveBtn)', function(data){
                $("#saveBtn").addClass("layui-btn-disabled");
                $("#saveBtn").attr('disabled', 'disabled');
                $.ajax({
                    url:'/admin/goods/batchIntegral',
                    type:'post',
                    data:data.field,
                    dataType:'JSON',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success:function(res){
                        if(res.code==0){
                            layer.msg(res.message,{icon: 1},function (){
                                parent.location.reload();
                            });
                        }else{
                            layer.msg(res.message,{icon: 2});
                            $("#saveBtn").removeClass("layui-btn-disabled");
                            $("#saveBtn").removeAttr('disabled');
                        }
                    },
                    error:function (data) {
                        layer.msg(res.message,{icon: 2});
                        $("#saveBtn").removeClass("layui-btn-disabled");
                        $("#saveBtn").removeAttr('disabled');
                    }
                });
            });
        });
    </script>
@endsection