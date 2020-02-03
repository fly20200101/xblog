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
                            <button class="layui-btn"  lay-submit="" lay-filter="addArticleType" onclick="addArticleType();"><i class="layui-icon"></i>增加</button>
                        </div>
                    <hr>
                    <blockquote class="layui-elem-quote">每个tr 上有两个属性 cate-id='1' 当前分类id fid='0' 父级id ,顶级分类为 0，有子分类的前面加收缩图标<i class="layui-icon x-show" status='true'>&#xe623;</i></blockquote>
                </div>
                <div class="layui-card-header">
                    <button class="layui-btn layui-btn-danger" onclick="delAll()">
                        <i class="layui-icon"></i>批量删除</button>
                </div>
                <div class="layui-card-body ">
                    <table class="layui-table layui-form" id="demo"></table>
                </div>
                <div class="layui-card-body ">
                    <div class="page">
                        <div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    layui.use(['form','table'], function(){
        form = layui.form;
        var table = layui.table;

        //第一个实例
        table.render({
            elem: '#demo'
            ,height: 312
            ,url: '/admin/article_type_list' //数据接口
            ,page: true //开启分页
            ,cols: [[ //表头
                {field: 'at_id', title: 'ID', width:80, sort: true, fixed: 'left'}
                ,{field: 'type_name', title: '分类名', width:80}
                ,{}
            ]]
        });
    });
    /*用户-删除*/
    function member_del(obj,id){
        layer.confirm('确认要删除吗？',function(index){
            //发异步删除数据
            $(obj).parents("tr").remove();
            layer.msg('已删除!',{icon:1,time:1000});
        });
    }

    // 分类展开收起的分类的逻辑
    //
    $(function(){
        $("tbody.x-cate tr[fid!='0']").hide();
        // 栏目多级显示效果
        $('.x-show').click(function () {
            if($(this).attr('status')=='true'){
                $(this).html('&#xe625;');
                $(this).attr('status','false');
                cateId = $(this).parents('tr').attr('cate-id');
                $("tbody tr[fid="+cateId+"]").show();
            }else{
                cateIds = [];
                $(this).html('&#xe623;');
                $(this).attr('status','true');
                cateId = $(this).parents('tr').attr('cate-id');
                getCateId(cateId);
                for (var i in cateIds) {
                    $("tbody tr[cate-id="+cateIds[i]+"]").hide().find('.x-show').html('&#xe623;').attr('status','true');
                }
            }
        })
    })

    var cateIds = [];
    function getCateId(cateId) {
        $("tbody tr[fid="+cateId+"]").each(function(index, el) {
            id = $(el).attr('cate-id');
            cateIds.push(id);
            getCateId(id);
        });
    }

    // 添加分类
    function addArticleType() {
        layer.open({
            type: 2
            , title: '添加文章分类'
            , content: '/admin/add_article_type_list'
            , area: ['600px', '600px']
            , maxmin: true
        });
    }

</script>
</body>
</html>
