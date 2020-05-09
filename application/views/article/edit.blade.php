@extends('layout.front')
@section('css')
<style>
    
</style>
@endsection

{{-- 改写layuiform元素样式 --}}
<link href="/public/lib/layui-v2.5.6/css/form.css" rel="stylesheet">
<link href="/public/lib/simplemde-1.11.2/simplemde.min.css" rel="stylesheet">
<link href="https://cdn.bootcdn.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
{{-- 代码高亮样式 --}}
<link href="https://cdn.bootcdn.net/ajax/libs/highlight.js/10.0.1/styles/atelier-forest-dark.min.css" rel="stylesheet">

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
              <input type="text" name="title" lay-verify="required" autocomplete="off" placeholder="请输入标题" class="layui-input">
          </div>

          <div class="layui-form-item">
              <input type="radio" name="article_type" value="10" title="原创">
              <input type="radio" name="article_type" value="20" title="转载">
              <input type="radio" name="article_type" value="30" title="私密">
          </div>

          <div class="layui-form-item">
            <select name="tag" multiple="" lay-search="" lay-verify="required">
                <option value=""></option>
                <option>sing1</option>
                <option>sing2</option>
                <option>SING1-大写</option>
                <option>movie1</option>
                <option>movie2</option>
                <option>movie3</option>
                <option>movie4</option>
                <option>movie5</option>
                <option>movie6</option>
                <option>movie7</option>
                <option>MOVIE4</option>
                <option>swim</option>
                <option>moon</option>
            </select>
          </div>

          <div class="layui-form-item layui-form-text">
              <textarea placeholder="请输入内容" name="content" class="layui-textarea" id="demo"></textarea>
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
  $(document).ready(function(){
    document.getElementById('content').innerHTML =
    marked('# Marked in browser\n\nRendered by **marked**.');
  })

</script>

@endsection

