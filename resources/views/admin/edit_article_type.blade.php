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
    <script type="text/javascript" src="/X-admin/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/X-admin/js/xadmin.js"></script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form" method="post">
            <div class="layui-form-item">
                <label for="username" class="layui-form-label">
                    <span class="x-red">*</span>分类名称
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="type_name" name="type_name" required="" lay-verify="required"
                           autocomplete="off" class="layui-input" value="{{$data['type_name']}}">
                    <input type="hidden" name="at_id" id="at_id" value="{{$data['at_id']}}">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>分类名称
                </div>
            </div>
            <div class="layui-form-item">
                <label for="username" class="layui-form-label">
                    <span class="x-red">*</span>排序
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="sort" name="sort" required="" lay-verify="required"
                           autocomplete="off" class="layui-input" value="{{$data['sort']}}">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>排序
                </div>
            </div>
            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">
                </label>
                <button  class="layui-btn" lay-filter="add_type_name" lay-submit="">
                    修改
                </button>
            </div>
        </form>
    </div>
</div>
<script>

    layui.use(['form', 'layer'],
        function() {
            $ = layui.jquery;
            var form = layui.form,
                layer = layui.layer;
            var csrf = "<?php echo csrf_token();?>"
            //监听提交
            form.on('submit(add_type_name)',
                function(data) {
                    $.ajax({
                        url:'/admin/edit_article_type_list',
                        method:"POST",
                        dataType:"JSON",
                        contentType: "application/json",
                        headers:{'X-CSRF-TOKEN':csrf},
                        data:JSON.stringify(data.field),
                        success:function (e) {
                            if(e.status){
                                layer.msg(e.message)
                                //关闭当前frame
                                xadmin.close();
                                // 可以对父窗口进行刷新
                                xadmin.father_reload();
                            }else{
                                layer.msg(e.message)
                            }
                        }
                    })
                    //发异步，把数据提交给php
                    return false;
                });

        });
</script>

</body>

</html>

