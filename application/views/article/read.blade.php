@extends('layout.front')
@section('content')
	<div class="container-wrap">
		<div class="container container-message container-details">
			<div class="contar-wrap">
				@if ($data)
					{{-- 文章详情 start--}}
					<div class="item">
						<div class="item-box  layer-photos-demo1 layer-photos-demo">
							<h3>{{ $data['title'] }}</h3>
							<h5>
								<span>发布于：{{ $data['created_at'] }}</span>
								<span class="mar-left">作者：{{ $data['name'] }}</span>
								<span class="mar-left">阅读数：{{ $data['visits'] }}</span>
							</h5>
							<p>{!! $data['content'] !!}</p>
							<div class="count layui-clear">
								<span class="pull-left">评论 <em>{{ $data['visits'] }}+</em></span>
								<span class="pull-right like">
									<i class="layui-icon layui-icon-praise"></i>
									<em>{{ $data['like'] }}</em>
								</span>
							</div>
						</div>
					</div>	
					{{-- 文章详情 end--}}

					{{-- 评论 start --}}
					@if ($comments)
						<a name="comment"></a>
						<div class="comt layui-clear">
							<a href="javascript:;" class="pull-left">评论</a>
							<a href="comment.html" class="pull-right">写评论</a>
						</div>
						<div id="LAY-msg-box">
							<div class="info-item">
								<img class="info-img" src="../res/static/images/info-img.png" alt="">
								<div class="info-text">
									<p class="title count">
										<span class="name">一片空白</span>
										<span class="info-img like"><i class="layui-icon layui-icon-praise"></i>5.8万</span>
									</p>
									<p class="info-intr">父爱如山，不善表达。回想十多年前，总记得父亲有个宽厚的肩膀，小小的自己跨坐在上面，越过人山人海去看更广阔的天空，那个时候期望自己有一双翅膀，能够像鸟儿一样飞得高，看得远。虽然父亲有时会和自己开玩笑，但在做错事的时候会受到严厉的训斥。父亲有双粗糙的大手掌。</p>
								</div>
							</div>
						</div>
					@endif
					{{-- 评论 end --}} 
				@else
					<h1>没有这个文章!</h1>
				@endif
			</div>
		</div>
	</div>
@endsection
