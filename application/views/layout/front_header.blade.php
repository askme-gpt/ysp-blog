@inject('menu','MenuModel')
@php
    $navs = $menu->index();
@endphp

<div class="header">
    <div class="header-wrap">
        <h1 class="logo pull-left">
            <a href="/index">
                <img src="/public/img/logo.png" alt="" class="logo-img">
                <img src="/public/img/logo-text.png" alt="" class="logo-text">
            </a>
        </h1>
        <form class="layui-form blog-seach pull-left" action="/article/index">
            <div class="layui-form-item blog-sewrap">
                <div class="layui-input-block blog-sebox">
                  <i class="layui-icon layui-icon-search"></i>
                  <input type="text" name="q" autocomplete="off" placeholder="输入要查询的词之后回车" class="layui-input" value="{{ $_GET['q'] ?? '' }}">
                </div>
            </div>
        </form>

        <div class="blog-nav">
            <a href="/xunsou/search?q={{ $_GET['q'] ?? '' }}" class="personal pull-left" 
            style="margin-left: 100px;">
                <i class="layui-icon">试试高级搜索</i>
            </a>
        </div>

        <div class="blog-nav pull-right">
            <ul class="layui-nav pull-left">
                @foreach ($navs as $element)
                  <li class="layui-nav-item 
                  {{ $base_url == $element['path'] ? 'layui-this' : '' }}
                  ">
                  <a href="{{ $element['path'] }}">{{ $element['name'] }}</a></li>
                @endforeach
            </ul>
            <a href="#" class="personal pull-left">
                <i class="layui-icon layui-icon-username"></i>
            </a>
        </div>
        <div class="mobile-nav pull-right" id="mobile-nav">
            <a href="javascript:;">
                <i class="layui-icon layui-icon-more"></i>
            </a>
        </div>
    </div>
    <ul class="pop-nav" id="pop-nav">
        <li><a href="/index" >首页</a></li>
        <li><a href="/article/add" >写文章</a></li>
        <li><a href="/message" >留言</a></li>
        <li><a href="/about" >关于</a></li>
    </ul>
</div>

<script>
    // alert(1231);
</script>