<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>闲言轻博客</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/public/lib/layui-v2.5.6/css/layui.css">
    <link rel="stylesheet" href="/public/css/mian.css">
    @yield('css')

</head>
<body class="lay-blog">
    @include('layout.front_header')
    @yield('content')
    @include('layout.front_footer')

    <script src="/public/lib/layui-v2.5.6/layui.js"></script>
    <script>
        layui.config({
          base: '/public/js/' 
        }).use('blog'); 
    </script>

    {{-- 下拉框多选 https://yelog.org/layui-select-multiple/ --}}
    
    @yield('js')
</body>
</html>