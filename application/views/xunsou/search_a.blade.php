<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={{ $oe }}" />
<meta name="googlebot" content="index,noarchive,nofollow,noodp" />
<meta name="robots" content="index,nofollow,noarchive,noodp" />
@php
  $url = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH) . '?q=' . urlencode($word);
@endphp
<title>

@if (!empty($q))
  搜索：{{ strip_tags($q) }}  - 
@endif
  搜索 - Powered by xunsearch
</title>
<meta http-equiv="keywords" content="Fulltext Search Engine Demo xunsearch" />
<meta http-equiv="description" content="Fulltext Search for Demo, Powered by xunsearch/1.4.14 " />
<link rel="stylesheet" type="text/css" href='/public/css/bootstrap.css'/>
<link rel="stylesheet" type="text/css" href='/public/css/xunsou.css'/>
<link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/jqueryui/1.12.1/jquery-ui.css" type="text/css" media="all" />
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!--[if lte IE 6]>
<link rel="stylesheet" type="text/css" href='/public/css/bootstrap-ie6.css' />
<link rel="stylesheet" type="text/css" href='/public/css/ie.css' />
<![endif]-->
</head>
<!-- search.tpl Demo 搜索模板 -->
<body>
<div class="container">
  <div class="row">
  <!-- search form -->
    <div class="span12">
      {{-- <h1><a href="{{ $_SERVER['REQUEST_URI'] }}"><img src="img/logo.jpg" /></a></h1> --}}
      <form class="form-search" id="q-form" method="get">
        <div class="input-append" id="q-input">
          <input type="text" class="span6 search-query" name="q" title="输入任意关键词皆可搜索" value="{{ htmlspecialchars($q) }}">
          <button type="submit" class="btn">搜索</button>
        </div>
        <div class="condition" id="q-options">
          <label class="radio inline"><input type="radio" name="f" value="title" {{ $f_title ?? '' }} />标题</label>
          <label class="radio inline">
            <input type="radio" name="f" value="_all" {{ $f__all ?? '' }} />全文
          </label>
          <label class="checkbox inline">
            <input type="checkbox" name="m" value="yes" {{ $m }} />模糊搜索
          </label>
          <label class="checkbox inline">
            <input type="checkbox" name="syn" value="yes" {{ $syn }} />同义词
          </label>
    </div>
      </form>
    </div>
    @if (!empty($q))
      @if (!empty($error))
        <p class="text-error"><strong>错误：</strong>{{ $error }}</p>
      @else
        <div class="span12">
          <p class="result">大约有
            <b>{{ number_format($count) }}</b>
            项符合查询结果，库内数据总量为
            <b>{{ number_format($total) }}</b>
            项。（搜索耗时：{{ printf('%.4f', $search_cost) }}秒
          </p>
      @endif
    @endif

    <!-- fixed query -->
    @if (count($corrected) > 0)
      <div class="link corrected">
        <h4>您是不是要找：</h4>
        <p>
          @foreach ($corrected as $word)
            <span>
              <a href="{{ $url }}" class="text-error">{{ $word }}</a>
            </span>
          @endforeach
        </p>
      </div>
    @endif

      <!-- empty result -->
      @if ($count === 0 && empty($error))
        <div class="demo-error">
          <p class="text-error">找不到和 <em>{{ htmlspecialchars($q) }}</em> 相符的内容或信息。</p>
          <h5>建议您：</h5>
          <ul>
            <li>1.请检查输入字词有无错误。</li>
            <li>2.请换用另外的查询字词。</li>
            <li>3.请改用较短、较为常见的字词。</li>
          </ul>
        </div>
      @endif

      <!-- result doc list -->
      <dl class="result-list">
        @foreach ($docs as $doc)

          <dt>
             <a href="javascript:void(alert('id {{ $doc->id }}'));">
               <h4>{{ $doc->rank() }}. {{ $search->highlight(htmlspecialchars($doc->title)) }}
                 <small>[{{ $doc->percent() }}%]</small>
               </h4>
             </a>
           </dt>
           <dd>
             <p>{{ $search->highlight(htmlspecialchars($doc->content)) }}</p>
             <p class="field-info text-error">
               <span><strong>更新时间:</strong>{{ htmlspecialchars($doc->updated_at) }}</span>
             </p>
           </dd>
        @endforeach
      </dl>

      <!-- pager -->
      @if (!empty($pager))
        <div class="pagination pagination-centered">
          <ul>
            <!--<li><a href="#">Prev</a></li>-->
            {{ $pager }}
            <!--<li><a href="#">Next</a></li>-->
          </ul>
        </div>
      @endif
    </div>
    <!-- end search result -->
  </div>
</div>

<!-- hot search -->
@if (count($hot) > 0)
  <section class="link">
    <div class="container">
      <h4>热门搜索:</h4>
      <p>
        @foreach ($hot as $word => $freq)
          <span>
            <a href="{{ $_SERVER['REQUEST_URI'] . '?q=' . urlencode($word) }}">{{ $word }}</a>
          </span>
        @endforeach
      </p>
    </div>
  </section>
@endif

<!-- related query -->
@if (count($related) > 0)
  <section class="link">
    <div class="container">
      <h4>相关搜索:</h4>
      <p>
        @foreach ($related as $word)
          <span>
            <a href="{{ $_SERVER['REQUEST_URI'] . '?q=' . urlencode($word) }}">{{ $word }}</a>
          </span>
        @endforeach
    </p>
    </div>
  </section>
@endif

<!-- footer -->
<footer>
  <div class="container">
    <p>(C)opyright 2020 - search - 页面处理总时间：<?php printf('%.4f', $total_cost);?>秒<br>
  </div>
</footer>
<script type="text/javascript" src="https://cdn.bootcdn.net/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.bootcdn.net/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">
$(function(){
  // input tips
  $('#q-input .search-query').focus(function(){
    if ($(this).val() == $(this).attr('title')) {
      $(this).val('').removeClass('tips');
    }
  }).blur(function(){
    if ($(this).val() == '' || $(this).val() == $(this).attr('title')) {
      $(this).addClass('tips').val($(this).attr('title'));
    }
  }).blur().autocomplete({
    'source':function (request, response) {
         //需要从后台查询数据
          $.ajax({
              url: "/xunsou/suggest",
              dataType: "json",
              data: {
                  term: request.term
              },
              success: function( data ) {
                  response( data.data );
              }
          });
      },,
    'select':function(ev,ui) {
      $('#q-input .search-query').val(ui.item.label);
      $('#q-form').submit();
    }
  });
  // submit check
  $('#q-form').submit(function(){
    var $input = $('#q-input .search-query');
    if ($input.val() == $input.attr('title')) {
      alert('请先输入关键词');
      $input.focus();
      return false;
    }
  });
});
</script>
</body>
</html>
