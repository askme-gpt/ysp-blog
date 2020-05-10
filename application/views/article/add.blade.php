@extends('layout.front')
@section('css')
  {{-- 改写layuiform元素样式 --}}
  <link href="/public/lib/layui-v2.5.6/css/form.css" rel="stylesheet">
  {{-- simplemd样式 --}}
  <link href="/public/lib/simplemde-1.11.2/simplemde.min.css" rel="stylesheet">
  
  <link href="https://cdn.bootcdn.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  {{-- 代码高亮样式 --}}
  <link href="https://cdn.bootcdn.net/ajax/libs/highlight.js/10.0.1/styles/atelier-forest-dark.min.css" rel="stylesheet">
  <style>
      .layui-form-label {
        text-align: left;
      }
  </style>
@endsection

<script src="https://cdn.bootcdn.net/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="/public/lib/simplemde-1.11.2/simplemde.min.js"></script>
{{-- 上传图片 start --}}
<script src="/public/lib/simplemde-1.11.2/inline-attachment.min.js"></script>
<script src="/public/lib/simplemde-1.11.2/codemirror-4.inline-attachment.min.js"></script>
{{-- 上传图片 end --}}
{{-- 代码高亮需要的js --}}
<script src="https://cdn.bootcdn.net/ajax/libs/highlight.js/10.0.1/highlight.min.js"></script>
{{-- 代码渲染需要的js --}}
{{-- <script src="https://cdn.bootcdn.net/ajax/libs/marked/1.0.0/marked.min.js"></script> --}}
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
            <label class="layui-form-label">标题：</label>
            <div class="layui-input-block">
              <input type="text" name="title" autofocus="autofocus" lay-verify="required" autocomplete="off" placeholder="请输入标题" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">类型：</label>
            <div class="layui-input-block">
              <input type="radio" name="type" value="10" title="原创">
              <input type="radio" name="type" value="20" title="转载">
              <input type="radio" name="type" value="30" title="私密">
            </div>
          </div>

          @if ($tags)
              <div class="layui-form-item">
                <label class="layui-form-label">标签：</label>
                <div class="layui-input-block">
                  <select name="tags[]" multiple lay-search="">
                      <option value=""></option>
                      @foreach ($tags as $tag)
                          <option value="{{ $tag['id'] }}">{{ $tag['name'] }}</option>
                      @endforeach
                  </select>
                </div>
              </div>
          @endif

          @if ($categories)
              <div class="layui-form-item">
                <label class="layui-form-label">分类：</label>
                <div class="layui-input-block">
                @cache(1,86400)
                  <select name="category_id" lay-search="" lay-verify="required">
                      <option value=""></option>
                      @foreach ($categories as $category)
                          <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                      @endforeach
                  </select>
                @endcache()
                </div>
              </div>
          @endif

          <div class="layui-form-item layui-form-text">
              <textarea placeholder="请输入内容" name="content" id="simplemd"></textarea>
          </div>

          <div class="layui-form-item">
              <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo1">提交</button>
              <button type="submit" class="layui-btn layui-btn-danger" lay-submit="" lay-filter="demo2">暂存</button>
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
    hljs.initHighlightingOnLoad();

    layui.use(['layer', 'form'], function(){
      var layer = layui.layer
      ,form = layui.form;

      form.on('submit(demo1)', function(data){
          layer.alert(JSON.stringify(data.field), {
            title: '最终的提交信息'
          });
          // return false;
        });
    });

    var editor = new SimpleMDE({
        element: document.getElementById("simplemd"),
        spellChecker: false,
        autofocus: false,
        autoDownloadFontAwesome: false,
        placeholder: "下笔如有神...",
        autosave: {
            enabled: true,
            uniqueId: "simplemd",
            delay: 1000,
        },
        tabSize: 4,
        status: false,
        lineWrapping: false,
        renderingConfig: {
            codeSyntaxHighlighting: true,
        },
    });

    editor.value('# Marked in browser\n\nRendered by **marked**.');
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

