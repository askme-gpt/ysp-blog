@extends('layout.front')

<link href="/public/lib/simplemde-1.11.2/simplemde.min.css" rel="stylesheet">
<link href="https://cdn.bootcdn.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<script src="https://cdn.bootcdn.net/ajax/libs/highlight.js/10.0.1/highlight.min.js"></script>
<link href="https://cdn.bootcdn.net/ajax/libs/highlight.js/10.0.1/styles/monokai-sublime.min.css" rel="stylesheet">

<script src="https://cdn.bootcdn.net/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="/public/lib/simplemde-1.11.2/simplemde.min.js"></script>
<script src="/public/lib/simplemde-1.11.2/inline-attachment.min.js"></script>
<script src="/public/lib/simplemde-1.11.2/codemirror-4.inline-attachment.min.js"></script>

@section('content')
<div class="container-wrap">
    <div class="container">
        <div class="contar-wrap">
            <h4 class="item-title">
                <p><i class="layui-icon layui-icon-speaker"></i>公告：<span>欢迎来到我的轻博客</span></p>
            </h4>
        </div>

        <form class="layui-form" action="/article/create" method="post">
          <div class="layui-form-item">
              <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入标题" class="layui-input">
          </div>

          <div class="layui-form-item layui-form-text">
              <textarea placeholder="请输入内容" name="content" class="layui-textarea" id="demo"></textarea>
          </div>

          <div class="layui-form-item">
              <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
              <button type="reset" class="layui-btn layui-btn-primary">重置</button>
          </div>
        </form>
    </div>
</div>


@endsection

@section('js')
{{-- 
https://www.jianshu.com/p/d09b35ca500d
https://learnku.com/articles/25988
https://learnku.com/articles/31749 
https://github.com/sparksuite/simplemde-markdown-editor
--}}
<script>
    $(document).ready(function() {
        var editor = new SimpleMDE({
        element: document.getElementById("demo"),
        spellChecker: false,
        autofocus: true,
        autoDownloadFontAwesome: false,
        placeholder: "下笔如有神...",
        autosave: {
            enabled: true,
            uniqueId: "demo",
            delay: 1000,
        },
        tabSize: 4,
        status: false,
        lineWrapping: false,
        renderingConfig: {
            codeSyntaxHighlighting: true,
        },
    });

    // editor.toggleSideBySide()//打开实时全屏预览

    var inlineAttachmentConfig = {
        uploadUrl: '/tool/uploadImage',               //后端上传图片地址
        uploadFieldName: 'upload_file',          //上传的文件名
        jsonFieldName: 'file_path',              //返回结果中图片地址对应的字段名称
        progressText: '![图片上传中...]()',    //上传过程中用户看到的文案
        errorText: '图片上传失败',
        urlText:'![图片描述]({filename})',    //上传成功后插入编辑器中的文案，{filename} 会被替换成图片地址
        extraHeaders: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    };
    //这里是 inlineAttachment 的调用配置
    inlineAttachment.editors.codemirror4.attach(editor.codemirror, inlineAttachmentConfig);
});
</script>
@endsection
{{-- 
$('#markdown-editor').on('paste', function(event) {
    // sth
}) --}}


