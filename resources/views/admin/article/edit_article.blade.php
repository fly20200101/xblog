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
                <label for="at_id" class="layui-form-label">
                    <span class="x-red">*</span>文章分类</label>
                <div class="layui-input-inline">
                    <select name="at_id" id="at_id">
                        @foreach($data as $k=>$v)
                            @if($v['at_id'] == $info['at_id'])
                                {{$v['at_id']}}2
                            <option value="{{$v['at_id']}}" selected="selected">{{$v['type_name']}}{{$info['at_id']}}</option>
                            @else
                                <option value="{{$v['at_id']}}">{{$v['type_name']}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="username" class="layui-form-label">
                    <span class="x-red">*</span>标题
                </label>
                <div class="layui-input-inline" style="z-index: 1">
                    <input type="text" id="title" name="title" required="" lay-verify="required"
                           autocomplete="off" class="layui-input" value="{{$info['title']}}">
                </div>
            </div>
            <hr>
            <div id="editor">
            </div>
            <script type="text/javascript" src="/js/wangeditor.js"></script>
            <script type="text/javascript">
                var E = window.wangEditor
                var editor = new E('#editor')
                // 或者 var editor = new E( document.getElementById('editor') )
                editor.customConfig.zIndex = 100
                editor.create()
                editor.txt.html('<?php echo $info["article_content"];?>')
            </script>



            <hr>

            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">
                </label>
                <button  class="layui-btn" lay-filter="edit_type_name" lay-submit="">
                    保存
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
            form.on('submit(edit_type_name)',
                function(data) {
                    var content = editor.txt.html();
                    data.field.content = content
                    data.field.id = {{$info['id']}}
                    $.ajax({
                        url:'/admin/edit_article',
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

