@extends('layout.front')
@section('content')
<div class="container-wrap">
    <div class="container">
        <div class="contar-wrap">
            <h4 class="item-title">
                <p><i class="layui-icon layui-icon-speaker"></i>公告：<span>欢迎来到我的轻博客</span></p>
            </h4>
            @if (!empty($data['list']) && is_array($data['list']))
                @foreach ($data['list'] as $element)
                    <div class="item">
                        <div class="item-box layer-photos-demo1 layer-photos-demo">
                            <h3>
                                <a href='/article/read?id={{ $element['id'] }}'>{{ $element['title'] }}</a>
                            </h3>
                            <h5>
                                <span>发布于：{{ $element['created_at'] }}</span>
                                <span class="mar-left">作者：{{ $element['name'] }}</span>
                                <span class="mar-left">阅读数：{{ $element['visits'] }}</span>
                            </h5>

                            <p id="content">{{ substr(str_replace(['#','|','![图片描述]','![',']','\r','\n','"','`','-','*',':'], '', strip_tags($element['content'])), 0,300) }}</p>
                            <img src="/public/img/item.png" alt="">
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div id="paginate"></div>
    </div>
</div>
<ul class="layui-fixbar">
    <li class="layui-icon layui-fixbar-top" lay-type="top" style="display: list-item;"></li>
</ul>
@endsection

@section('js')
<script>
layui.use(['jquery','laypage'], function(){
  var laypage = layui.laypage;
  var $ = layui.jquery;
  
  //执行一个laypage实例
  laypage.render({
    elem: 'paginate' //注意，这里的 paginate 是 ID，不用加 # 号
    ,count: {{ $data['count'] ?: 0 }} //数据总数，从服务端得到
    ,curr:{{ $_GET['page'] ?? 1 }}
    ,jump: function(obj, first){
        //obj包含了当前分页的所有参数，比如：
        //首次不执行
        if(!first){
            obj = {
                q:$('input[name=q]').val(),
                page:obj.curr,
                limit:obj.limit
            };
            
            var href = '{{ $base_url }}?' + $.param(obj);
            location.href = href;
        }
      }
  });
});
</script>
@endsection




