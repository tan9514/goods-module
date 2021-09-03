@extends('admin.public.header')
@section('title',$title)
@section('listcontent')
    <div class="layui-form layuimini-form">
        @if(isset($info->id))
        <input type="hidden" name="id" value="{{$info->id}}" />
        @endif

        <div class="layui-form-item">
            <label class="layui-form-label">上级分类</label>
            <div class="layui-input-block">
                <select name="parent_id" lay-search>
                    <option value="0">顶级分类</option>
                    @foreach($catArr as $catInfo)
                        <option value="{{$catInfo->id}}" @if(isset($info->parent_id) && $info->parent_id == $catInfo->id) selected @endif>{{$catInfo->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">分类名称</label>
            <div class="layui-input-block">
                <input type="text" name="name" lay-verify="required" lay-reqtext="分类名称不能为空" placeholder="请输入分类名称" value="{{$info->name ?? ''}}" class="layui-input" />
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">小图标</label>
            <div class="layui-input-block">
                <div class="layui-upload-drag uploadImgesPic" data-showImgId="showSmallPicImages" data-input="small_pic">
                    <i class="layui-icon"></i>
                    <p>点击上传，或将文件拖拽到此处</p>
                    <br>
                    <div class=" {{$info->show_small_pic ? '' : 'layui-hide'}}" id="showSmallPicImages">
                        <img src="{{$info->show_small_pic ?? ''}}" alt="上传成功后渲染" style="max-width: 196px">
                    </div>
                    <input type="hidden" name="small_pic" value="{{$info->small_pic ?? ''}}" />
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">大图标</label>
            <div class="layui-input-block">
                <div class="layui-upload-drag uploadImgesPic" data-showImgId="showLargePicImages" data-input="large_pic">
                    <i class="layui-icon"></i>
                    <p>点击上传，或将文件拖拽到此处</p>
                    <br>
                    <div class="{{$info->show_large_pic ? '' : 'layui-hide'}}" id="showLargePicImages">
                        <img src="{{$info->show_large_pic ?? ''}}" alt="上传成功后渲染" style="max-width: 196px">
                    </div>
                    <input type="hidden" name="large_pic" value="{{$info->large_pic ?? ''}}" />
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">长图标</label>
            <div class="layui-input-block">
                <div class="layui-upload-drag uploadImgesPic" data-showImgId="showLongPicImages" data-input="long_pic">
                    <i class="layui-icon"></i>
                    <p>点击上传，或将文件拖拽到此处</p>
                    <br>
                    <div class="{{$info->show_long_pic ? '' : 'layui-hide'}}" id="showLongPicImages">
                        <img src="{{$info->show_long_pic ?? ''}}" alt="上传成功后渲染" style="max-width: 196px">
                    </div>
                    <input type="hidden" name="long_pic" value="{{$info->long_pic ?? ''}}" />
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">广告图</label>
            <div class="layui-input-block">
                <div class="layui-upload-drag uploadImgesPic" data-showImgId="showAdPicImages" data-input="ad_pic">
                    <i class="layui-icon"></i>
                    <p>点击上传，或将文件拖拽到此处</p>
                    <br>
                    <div class="{{$info->show_ad_pic ? '' : 'layui-hide'}}" id="showAdPicImages">
                        <img src="{{$info->show_ad_pic ?? ''}}" alt="上传成功后渲染" style="max-width: 196px">
                    </div>
                    <input type="hidden" name="ad_pic" value="{{$info->ad_pic ?? ''}}" />
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">广告图外链地址</label>
            <div class="layui-input-block">
                <input type="text" name="ad_url"  placeholder="请输入广告图外链地址" value="{{$info->ad_url ?? ''}}" class="layui-input" />
                <div style="font-size: 10px; color: red;">如果存在广告图外链地址跳转，请输入以http://或者https://开头的域名全地址</div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">推荐到首页</label>
            <div class="layui-input-block">
                @foreach($info->getRecommendArr() as $k=>$v)
                    <input type="radio" name="is_recommend" value="{{$k}}" title="{{$v}}" @if($k == $info->is_recommend) checked="" @endif />
                @endforeach
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">是否显示</label>
            <div class="layui-input-block">
                @foreach($info->getShowArr() as $k=>$v)
                    @if(isset($info->is_show))
                        <input type="radio" name="is_show" value="{{$k}}" title="{{$v}}" @if($k == $info->is_show) checked="" @endif />
                    @else
                        <input type="radio" name="is_show" value="{{$k}}" title="{{$v}}" @if($k == 1) checked="" @endif />
                    @endif
                @endforeach
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">排序</label>
            <div class="layui-input-block">
                <input type="number" name="sort" lay-verify="required" lay-reqtext="排序不能为空" placeholder="请输入排序" value="{{$info->sort ?? 100}}" class="layui-input" />
                <div style="font-size: 10px; color: red;">排序值只能为大于等于0 ~ 小于等于100。</div>
            </div>
        </div>

        <div class="hr-line"></div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-normal" id="saveBtn" lay-submit lay-filter="saveBtn">保存</button>
            </div>
        </div>

    </div>
@endsection

@section('listscript')
    <script type="text/javascript">
        layui.use(['iconPickerFa', 'form', 'layer', 'upload'], function () {
            var iconPickerFa = layui.iconPickerFa,
                form = layui.form,
                layer = layui.layer,
                upload = layui.upload,
                $ = layui.$;

            upload.render({
                elem: ".uploadImgesPic"
                ,url: '/admin/upload/upload' //改成您自己的上传接口
                ,accept: 'images'
                ,acceptMime: 'image/*'
                ,size: 500 //限制文件大小，单位 KB
                ,headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                ,done: function(res){
                    var item = this.item.context.dataset.showimgid;
                    var inputname = this.item.context.dataset.input;
                    if(item.length <= 0) layer.msg("程序错误",{icon: 2});
                    if(res.code==0){
                        layer.msg("上传成功",{icon: 1});
                        var domain = window.location.host;
                        layui.$('#' + item).removeClass('layui-hide').find('img').attr('src', "http://" + domain + "/" + res.data[0]);
                        $("input[name='"+inputname+"']").val(res.data[0]);
                    }else{
                        layer.msg(res.message,{icon: 2});
                        layui.$('#' + item).addClass('layui-hide');
                        $("input[name='"+inputname+"']").val('');
                    }
                }
            });

            //监听提交
            form.on('submit(saveBtn)', function(data){
                $("#saveBtn").addClass("layui-btn-disabled");
                $("#saveBtn").attr('disabled', 'disabled');
                $.ajax({
                    url:'/admin/goods_cat/edit',
                    type:'post',
                    data:data.field,
                    dataType:'JSON',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success:function(res){
                        if(res.code==0){
                            // setTimeout('parent.location.reload()',2000);
                            // var index = parent.layer.getFrameIndex(window.name);
                            layer.msg(res.message,{icon: 1},function (){
                                // parent.layer.close(index)
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