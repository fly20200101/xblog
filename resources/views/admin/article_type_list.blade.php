<!DOCTYPE html>
<html class="x-admin-sm">

<head>
    <meta charset="UTF-8">
    <title>欢迎页面-X-admin2.2</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="stylesheet" href="/X-admin/css/font.css">
    <link rel="stylesheet" href="/X-admin/css/xadmin.css">
    <script type="text/javascript" src="/X-admin/js/jquery.min.js"></script>
    <script src="/X-admin/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/X-admin/js/xadmin.js"></script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<div class="x-nav">
            <span class="layui-breadcrumb">
                <a href="">首页</a>
                <a href="">演示</a>
                <a>
                    <cite>导航元素</cite></a>
            </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
    </a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                        <div class="layui-input-inline layui-show-xs-block">
                            <button class="layui-btn"  lay-submit="" onclick="addArticleType()"><i class="layui-icon"></i>增加</button>
                        </div>
                    <hr>

                </div>
                <div class="layui-card-header">
                    <button class="layui-btn layui-btn-danger" onclick="delAll()">
                        <i class="layui-icon"></i>批量删除</button>
                </div>
                <div class="layui-card-body ">
                    <table class="layui-table layui-form" id="demo" lay-filter="article_type_table"></table>
                    <script type="text/html" id="table-operate-action">
                        <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit"><i
                                    class="layui-icon layui-icon-edit"></i>编辑</a>
                        <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="addChildType"><i
                                    class="layui-icon layui-icon-edit"></i>添加子分类</a>
                        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delType"><i class="layui-icon layui-icon-delete"></i>删除</a>
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    layui.use(['form','table'], function(){
        var csrf = "{{ csrf_token() }}"
        form = layui.form;
        var table = layui.table;
        //第一个实例
        table.render({
            elem: '#demo'
            ,url: '/admin/article_type_list' //数据接口
            , page: true
            ,limit:5
            ,smartReloadModel: true
            ,cols: [[ //表头
                {field: 'at_id', title: 'ID', sort: true, fixed: 'left'}
                ,{field: 'type_name', title: '分类名'}
                ,{field: 'sort', title: '排序',sort: true},
                ,{title: '操作', toolbar: '#table-operate-action'}
            ]],
            text: {none: '未查询到数据^_^'}
        });
        table.on('tool(article_type_table)', function (obj) {
            var data = obj.data; //获得当前行数据
            var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
            var tr = obj.tr; //获得当前行 tr 的DOM对象
            if (layEvent === "edit") {
                layer.open({
                    type: 2
                    , title: '编辑文章分类'
                    , content: '/admin/edit_article_type_list?at_id='+data.at_id
                    , area: ['600px', '600px']
                    , maxmin: true
                });
            }


            if(layEvent === "addChildType"){
                layer.open({
                    type: 2
                    , title: '添加文章分类'
                    , content: '/admin/add_child_article_type?at_id='+data.at_id
                    , area: ['600px', '600px']
                    , maxmin: true
                });
            }

            if(layEvent === "delType"){
                layer.confirm('确认要删除吗？',function(index){
                    console.log(index)
                    $.ajax({
                        url:'/admin/delArticleType',
                        method:'post',
                        dataType:'json',
                        headers:{'X-CSRF-TOKEN':csrf},
                        contentType: "application/json",
                        data:JSON.stringify({'act':'del','at_id':index}),
                        success:function (e) {
                            if(e.status){
                                window.reload()
                            }else{
                                layui.msg(e.message)
                            }
                        }
                    })
                });
            }
        })
    });
    function addArticleType() {
        layer.open({
            type: 2
            , title: '添加文章分类'
            , content: '/admin/add_article_type_list'
            , area: ['600px', '600px']
            , maxmin: true
        });
    }
    var cateIds = [];
    function getCateId(cateId) {
        $("tbody tr[fid="+cateId+"]").each(function(index, el) {
            id = $(el).attr('cate-id');
            cateIds.push(id);
            getCateId(id);
        });
    }

</script>
</body>
</html>
