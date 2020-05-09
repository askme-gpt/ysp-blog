@extends('layout.front')
@section('content')
<div class="container-wrap">
    <div class="container">
        <div class="contar-wrap">
            <h4 class="item-title">
                <p><i class="layui-icon layui-icon-speaker"></i>公告：<span>欢迎来到我的轻博客</span></p>
            </h4>
            @foreach ($data['list'] as $element)
                <div class="item">
                    <div class="item-box layer-photos-demo1 layer-photos-demo">
                        <h3>
                        	<a href='/article/read/{{ $element['id'] }}'>{{ $element['title'] }}</a>
                        </h3>
                        <h5>
                        	<span>发布于：{{ $element['created_at'] }}</span>
                        	<span class="mar-left">作者：{{ $element['name'] }}</span>
                        	<span class="mar-left">阅读数：{{ $element['visits'] }}</span>
                        </h5>
                        <p id="content">{{ $element['content'] }}</p>
                        <img src="/public/img/item.png" alt="">
                    </div>
                </div>
            @endforeach
        </div>
        <div class="item-btn">
            <button class="layui-btn">下一页</button>
        </div>

    </div>
</div>
<ul class="layui-fixbar">
    <li class="layui-icon layui-fixbar-top" lay-type="top" style="display: list-item;"></li>
</ul>
@endsection



@section('js')
    <script src="https://cdn.bootcdn.net/ajax/libs/marked/1.0.0/marked.min.js"></script>
    <script>
        document.getElementById('content').innerHTML =
        marked('# Marked in browser\n\nRendered by **marked**.');
    </script>
@endsection




