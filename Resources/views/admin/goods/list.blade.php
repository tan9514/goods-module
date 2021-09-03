@extends('admin.public.header')
@section('title','商品列表')

@section('listsearch')
    <fieldset class="table-search-fieldset" style="display:none">
        <legend>搜索信息</legend>
        <div style="margin: 10px 10px 10px 10px">
            <form class="layui-form layui-form-pane form-search" action="" id="searchFrom">
                <div class="layui-form-item">
                    <div class="layui-form-item" style="width: 50%;">
                        <label class="layui-form-label" style="height: 38px; line-height: 36px;">分类</label>
                        <div class="layui-input-block">
                            <div id="goodsCat"></div>
                        </div>
                    </div>

                    <div class="layui-inline">
                        <label class="layui-form-label">状态</label>
                        <div class="layui-input-inline">
                            <select name="status" lay-search>
                                <option value="">全部</option>
                                @foreach($statusArr as $k => $statusInfo)
                                    <option value="{{$k}}">{{$statusInfo}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="layui-inline">
                        <label class="layui-form-label">快速购买</label>
                        <div class="layui-input-inline">
                            <select name="quick_purchase" lay-search>
                                <option value="">全部</option>
                                @foreach($quickArr as $qk => $quickInfo)
                                    <option value="{{$qk}}">{{$quickInfo}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <br>
                    <div class="layui-inline">
                        <label class="layui-form-label">商品名称</label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" placeholder="商品名称模糊查询" class="layui-input" />
                        </div>
                    </div>

                    <br>
                    <div class="layui-inline">
                        <button type="submit" class="layui-btn layui-btn-sm layui-btn-normal"  lay-submit lay-filter="data-search-btn"><i class="layui-icon"></i> 搜 索</button>
                    </div>
                </div>
            </form>
        </div>
    </fieldset>
@endsection

@section('listcontent')
    <table class="layui-hide" id="tableList" lay-filter="tableList"></table>
    <!-- 表头左侧按钮 -->
    <script type="text/html" id="toolbarColumn">
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm layuimini-btn-primary" onclick="window.location.reload();" ><i class="layui-icon layui-icon-refresh-3"></i></button>
            <button class="layui-btn layui-btn-normal layui-btn-sm data-add-btn" lay-event="add"><i class="layui-icon layui-icon-add-circle"></i>新增</button>
            <button class="layui-btn layui-btn-sm layui-bg-red" lay-event="batch_delete"><i class="layui-icon layui-icon-delete"></i>批量删除</button>
            <button class="layui-btn layui-btn-sm" lay-event="batch_status_ok"><i class="layui-icon layui-icon-ok-circle"></i>批量上架</button>
            <button class="layui-btn layui-btn-sm" lay-event="batch_status_close"><i class="layui-icon layui-icon-close-fill"></i>批量下架</button>
            <button class="layui-btn layui-btn-sm" lay-event="batch_quick_ok"><i class="layui-icon layui-icon-ok-circle"></i>批量开启快速购买</button>
            <button class="layui-btn layui-btn-sm" lay-event="batch_quick_close"><i class="layui-icon layui-icon-close-fill"></i>批量关闭快速购买</button>
            <button class="layui-btn layui-btn-sm" lay-event="batch_integral"><i class="layui-icon layui-icon-layer"></i>批量设置积分</button>
        </div>
    </script>

    <!-- 操作按钮 -->
    <script type="text/html" id="barOperate">
        <a class="layui-btn layui-btn-xs" lay-event="get-qrcode">商品二维码</a>
        <a class="layui-btn layui-btn-xs" lay-event="edit"><i class="layui-icon layui-icon-edit"></i>编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="layui-icon layui-icon-delete"></i>删除</a>
    </script>
@endsection

@section('listscript')
    <script type="text/javascript">
        layui.use(['form','table','laydate','xmSelect'], function(){
            var table = layui.table, $=layui.jquery, form = layui.form, laydate = layui.laydate, xmSelect = layui.xmSelect;

            // 渲染属性多选
            function setXmSelect(id, name, data){
                xmSelect.render({
                    el: '#' + id,
                    name:name,
                    toolbar: {
                        show: true,
                    },
                    filterable: true,
                    theme: {
                        color: '#a5673f',
                    },
                    tree: {
                        //是否显示树状结构
                        show: true,
                        //是否展示三角图标
                        showFolderIcon: true,
                        //是否显示虚线
                        showLine: false,
                        //间距
                        indent: 20,
                        //默认展开节点的数组, 为 true 时, 展开所有节点
                        expandedKeys: [],
                        //是否严格遵守父子模式
                        strict: false,
                        //是否开启极简模式
                        simple: false,
                        //点击节点是否展开
                        clickExpand: true,
                        //点击节点是否选中
                        clickCheck: true,
                    },
                    data: data
                })
            }

            $.ajax({
                url:'/admin/goods/xmSelect',
                type:'post',
                data:{},
                dataType:'JSON',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success:function(res){
                    if(res.code==0){
                        setXmSelect("goodsCat", "cat_ids", res.data.goodsCat);
                    }else{
                        layer.msg(res.message,{icon: 2});
                    }
                },
                error:function (data) {
                    layer.msg(res.message,{icon: 2});
                }
            });

            // 渲染表格
            table.render({
                elem: '#tableList',
                url:'/admin/goods/ajaxList',
                parseData: function(res) { //res 即为原始返回的数据
                    return {
                        "code": res.code, //解析接口状态
                        "msg": res.message, //解析提示文本
                        "count": res.data.count, //解析数据长度
                        "data": res.data.list //解析数据列表
                    }
                },
                cellMinWidth: 80,//全局定义常规单元格的最小宽度
                toolbar: '#toolbarColumn',//开启头部工具栏，并为其绑定左侧模板
                defaultToolbar: [
                    'filter',
                    'exports',
                    'print',
                    { //自定义头部工具栏右侧图标。如无需自定义，去除该参数即可
                        title: '搜索',
                        layEvent: 'TABLE_SEARCH',
                        icon: 'layui-icon-search'
                    }
                ],
                title: '商品列表',
                cols: [[
                    {type: 'checkbox', align: 'center'},
                    {field:'id', title:'ID', width:80, align: 'center', unresize: true, sort: true},
                    {field:'catArr', title:'分类', width: 200,
                        templet: function (info){
                            let spanTest = "";
                            for (let i = 0; i < info.catArr.length; i++){
                                spanTest += "<span style='background-color: rgb(165, 103, 63); color: #fff; padding: height: 26px; line-height: 26px; display: inline-block; position: relative; padding: 0px 5px; margin: 2px 5px 2px 0; border-radius: 3px; align-items: baseline;'>"+info.catArr[i]+"</span>";
                            }
                            return spanTest;
                        }
                    },
                    {field:'name', title:'商品名称', width:250},
                    {field: 'pic', title: '缩略图', align: 'center', width:80, event: 'show_pic',
                        templet: function (info){
                            if(info.show_pic == "") return "";
                            return '<a class="showPicImages" href="javascript:void(0)" data-src="' + info.show_pic + '" title="点击查看"><img style="width:50px;" src="'+info.show_pic+'"></a>'
                        }
                    },
                    {field:'price', title:'售价', align: 'center', width: 100},
                    {field:'goods_num', title:'库存', align: 'center', width: 100},
                    {field:'status', title:'状态', width:100, align: 'center',
                        templet: function(info){
                            if(info.status == 1){
                                return '<input type="checkbox" name="status" value="'+info.id+'" lay-skin="switch" lay-text="{{$statusTest}}" lay-filter="isOpen" checked>'
                            }else{
                                return '<input type="checkbox" name="status" value="'+info.id+'" lay-skin="switch" lay-text="{{$statusTest}}" lay-filter="isOpen">'
                            }
                        }
                    },
                    {field:'quick_purchase', title:'快速购买', width:100, align: 'center',
                        templet: function(info){
                            if(info.quick_purchase == 1){
                                return '<input type="checkbox" name="quick_purchase" value="'+info.id+'" lay-skin="switch" lay-text="{{$quickTest}}" lay-filter="isQuickPurchase" checked>'
                            }else{
                                return '<input type="checkbox" name="quick_purchase" value="'+info.id+'" lay-skin="switch" lay-text="{{$quickTest}}" lay-filter="isQuickPurchase">'
                            }
                        }
                    },
                    {field:'virtual_sales', title:'虚拟销量', align: 'center', width: 100},
                    {field:'sort', title:'排序', width:80, align: 'center', sort: true, edit: true},
                    {title:'操作', toolbar: '#barOperate', align: 'center'}
                ]],
                id: 'listReload',
                limits: [10, 20, 30, 50, 100,200],
                limit: 10,
                page: true,
                text: {
                    none: '抱歉！暂无数据~' //默认：无数据。注：该属性为 layui 2.2.5 开始新增
                }
            });

            //头工具栏事件
            table.on('toolbar(tableList)', function(obj){
                var checkStatus = table.checkStatus(obj.config.id);
                var ids = [];
                var data = checkStatus.data;
                for (var i=0;i<data.length;i++){
                    ids.push(data[i].id);
                }
                switch(obj.event){
                    case "add": // 新增
                        var index = layer.open({
                            title: '新增商品',
                            type: 2,
                            shade: 0.2,
                            maxmin:true,
                            skin:'layui-layer-lan',
                            shadeClose: true,
                            area: ['90%', '80%'],
                            content: '/admin/goods/edit',
                        });
                        break;
                    case "batch_delete": // 批量删除
                        if(!ids.length){
                            return layer.msg('请勾选要删除的商品数据',{icon: 2});
                        }
                        layer.confirm('确定删除选中的商品数据吗？', {
                            title : "操作确认",
                            skin: 'layui-layer-lan'
                        },function(index){
                            $.ajax({
                                url:'/admin/goods/del',
                                type:'post',
                                data:{'id':ids},
                                dataType:"JSON",
                                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                success:function(data){
                                    if(data.code == 0){
                                        layer.msg(data.message,{icon: 1,time:1500},function(){
                                            table.reload('listReload');
                                        });
                                    }else{
                                        layer.msg(data.message,{icon: 2});
                                    }
                                },
                                error:function(e){
                                    layer.msg(data.message,{icon: 2});
                                },

                            });
                        });
                        break;
                    case 'batch_status_ok': // 批量上架
                        if(!ids.length){
                            return layer.msg('请勾选要上架的商品数据',{icon: 2});
                        }
                        layer.confirm('确定要上架勾选的商品吗？', {
                            title : "上架商品",
                            skin: 'layui-layer-lan'
                        },function(index){
                            $.ajax({
                                url:'/admin/goods/saveStatus',
                                type:'post',
                                data:{
                                    'id':ids,
                                    'status': true
                                },
                                dataType:"JSON",
                                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                success:function(data){
                                    if(data.code == 0){
                                        layer.msg(data.message,{icon: 1,time:1500},function(){
                                            table.reload('listReload');
                                        });
                                    }else{
                                        layer.msg(data.message,{icon: 2});
                                    }
                                },
                                error:function(e){
                                    layer.msg(data.message,{icon: 2});
                                },

                            });
                        });
                        break
                    case 'batch_status_close': // 批量下架
                        if(!ids.length){
                            return layer.msg('请勾选要下架的商品数据',{icon: 2});
                        }
                        layer.confirm('确定要下架勾选的商品吗？', {
                            title : "下架商品",
                            skin: 'layui-layer-lan'
                        },function(index){
                            $.ajax({
                                url:'/admin/goods/saveStatus',
                                type:'post',
                                data:{
                                    'id':ids,
                                    'status': false
                                },
                                dataType:"JSON",
                                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                success:function(data){
                                    if(data.code == 0){
                                        layer.msg(data.message,{icon: 1,time:1500},function(){
                                            table.reload('listReload');
                                        });
                                    }else{
                                        layer.msg(data.message,{icon: 2});
                                    }
                                },
                                error:function(e){
                                    layer.msg(data.message,{icon: 2});
                                },

                            });
                        });
                        break;
                    case 'batch_quick_ok': // 批量开启快速购买
                        if(!ids.length){
                            return layer.msg('请勾选要开启快速购买的商品数据',{icon: 2});
                        }
                        layer.confirm('勾选的商品确定要开启快速购买吗？', {
                            title : "快速购买",
                            skin: 'layui-layer-lan'
                        },function(index){
                            $.ajax({
                                url:'/admin/goods/saveQuick',
                                type:'post',
                                data:{
                                    'id':ids,
                                    'quick_purchase': true
                                },
                                dataType:"JSON",
                                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                success:function(data){
                                    if(data.code == 0){
                                        layer.msg(data.message,{icon: 1,time:1500},function(){
                                            table.reload('listReload');
                                        });
                                    }else{
                                        layer.msg(data.message,{icon: 2});
                                    }
                                },
                                error:function(e){
                                    layer.msg(data.message,{icon: 2});
                                },

                            });
                        });
                        break
                    case 'batch_quick_close': // 批量关闭快速购买
                        if(!ids.length){
                            return layer.msg('请勾选要关闭快速购买的商品数据',{icon: 2});
                        }
                        layer.confirm('勾选的商品确定要关闭快速购买吗？', {
                            title : "快速购买",
                            skin: 'layui-layer-lan'
                        },function(index){
                            $.ajax({
                                url:'/admin/goods/saveQuick',
                                type:'post',
                                data:{
                                    'id':ids,
                                    'quick_purchase': false
                                },
                                dataType:"JSON",
                                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                success:function(data){
                                    if(data.code == 0){
                                        layer.msg(data.message,{icon: 1,time:1500},function(){
                                            table.reload('listReload');
                                        });
                                    }else{
                                        layer.msg(data.message,{icon: 2});
                                    }
                                },
                                error:function(e){
                                    layer.msg(data.message,{icon: 2});
                                },

                            });
                        });
                        break;
                    case 'batch_integral': // 批量设置积分
                        if(!ids.length){
                            return layer.msg('请勾选要批量设置积分的商品数据',{icon: 2});
                        }
                        layer.open({
                            title: '积分设置',
                            type: 2,
                            shade: 0.2,
                            maxmin:true,
                            skin:'layui-layer-lan',
                            shadeClose: true,
                            area: ['60%', '80%'],
                            content: '/admin/goods/batchIntegral?ids='+ids,
                        });
                        break;
                    case 'TABLE_SEARCH': // 搜索功能
                        var display = $(".table-search-fieldset").css("display"); //获取标签的display属性
                        if(display == 'none'){
                            $(".table-search-fieldset").show();
                        }else{
                            $(".table-search-fieldset").hide();
                        }
                        break;
                };
            });

            // 监听行工具事件
            table.on('tool(tableList)', function(obj){
                var data = obj.data;
                var id = data.id;
                switch (obj.event){
                    case "get-qrcode": // 获取商品二维码
                        return layer.msg("获取商品二维码",{icon: 2});
                        break;
                    case "edit":  // 编辑功能
                        var index = layer.open({
                            title: data.name + ' - 编辑',
                            type: 2,
                            shade: 0.2,
                            maxmin:true,
                            skin:'layui-layer-lan',
                            shadeClose: true,
                            area: ['80%', '80%'],
                            content: '/admin/goods/edit?id='+id,
                        });
                        break;
                    case "del":  // 删除功能
                        layer.confirm('确定删除 ' + data.name + ' 吗？', {
                            title : "删除商品",
                            skin: 'layui-layer-lan'
                        },function(index){
                            $.ajax({
                                url:'/admin/goods/del',
                                type:'post',
                                data:{'id':id},
                                dataType:"JSON",
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                success:function(data){
                                    if(data.code == 0){
                                        layer.msg(data.message,{icon: 1,time:1500},function(){
                                            table.reload('listReload');
                                        });
                                    }else{
                                        layer.msg(data.message,{icon: 2});
                                    }
                                },
                                error:function(e){
                                    layer.msg(data.message,{icon: 2});
                                },
                            });
                        });
                        break;
                    case "show_pic":    // 缩略图展示
                        if(data.show_pic != "") {
                            var img_infor = "<img src='" + data.show_pic + "' />";
                            layer.open({
                                title: false,
                                type: 1,
                                closeBtn: 0,
                                area: ['auto'],
                                skin: 'layui-layer-nobg', //没有背景色
                                shadeClose: true,
                                content: img_infor,
                            });
                        }
                        break;
                }
            });

            // 表格修改事件
            table.on('edit(tableList)', function (obj) {
                var id = obj.data.id;
                var field = obj.field;
                var value = obj.value;
                if(value == ""){
                    layer.msg("修改的值不能未空",{icon: 2, time: 1000},function(){
                        window.location.reload();
                    });
                }else{
                    $.ajax({
                        url:'/admin/goods/saveSort',
                        type:'post',
                        data:{
                            id: id,
                            field: field,
                            value: value,
                        },
                        dataType:"JSON",
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success:function(data){
                            if(data.code == 0){
                                layer.msg(data.message,{icon: 1,time:1000},function(){
                                    window.location.reload();
                                });
                            }else{
                                layer.msg(data.message,{icon: 2,time:1000},function(){
                                    window.location.reload();
                                });
                            }
                        },
                        error: function (){
                            layer.msg("请求失败",{icon: 2},function(){
                                window.location.reload();
                            });
                        }
                    });
                }
            });

            //监听上下架操作
            form.on('switch(isOpen)', function(obj){
                var checked = obj.elem.checked;
                var id = obj.value;
                $.ajax({
                    url:'/admin/goods/saveStatus',
                    type:'post',
                    data:{'status':checked,'id':id},
                    dataType:"JSON",
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success:function(data){
                        if(data.code == 0){
                            layer.msg(data.message,{icon: 1,time:1500});
                        }else{
                            layer.msg(data.message,{icon: 2},function(){
                                window.location.reload();
                            });
                        }
                    },
                    error: function (){
                        layer.msg("请求失败",{icon: 2},function(){
                            window.location.reload();
                        });
                    }
                });
            });

            //监听快速购买操作
            form.on('switch(isQuickPurchase)', function(obj){
                var checked = obj.elem.checked;
                var id = obj.value;
                $.ajax({
                    url:'/admin/goods/saveQuick',
                    type:'post',
                    data:{'quick_purchase':checked,'id':id},
                    dataType:"JSON",
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success:function(data){
                        if(data.code == 0){
                            layer.msg(data.message,{icon: 1,time:1500});
                        }else{
                            layer.msg(data.message,{icon: 2},function(){
                                window.location.reload();
                            });
                        }
                    },
                    error: function (){
                        layer.msg("请求失败",{icon: 2},function(){
                            window.location.reload();
                        });
                    }
                });
            });

            // 监听搜索操作
            form.on('submit(data-search-btn)', function (data) {
                //执行搜索重载
                table.reload('listReload', {
                    where: data.field,
                    page: {
                        curr: 1
                    }
                });
                return false;
            });
        });
    </script>
@endsection
