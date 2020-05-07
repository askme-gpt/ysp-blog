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
								<span class="like pull-right">
									<i class="layui-icon layui-icon-praise"></i>
									<em>{{ $data['like'] }}</em>
								</span>
							</h5>
							<p>{!! $data['content'] !!}</p>
							<div class="count layui-clear">
								@if ($data['updated_at'] !== $data['created_at'])
									<span>更新于：{{ $data['updated_at'] }}</span>
								@endif
								<a href="/reply" class="pull-right">写评论</a>
							</div>
						</div>
					</div>	
					{{-- 文章详情 end--}}

					{{-- 评论 start --}}
					@if ($comments)
						<div id="LAY-msg-box">
							@foreach ($comments as $comment)
								<div class="info-item">
									<img class="info-img" src="/public/img/info-img.png" alt="">
									<div class="info-text">
										<p class="title count">
											<span class="name">{{ $comment['name'] }}</span>
											<span class="name mar-left">发表时间：{{ $comment['created_at'] }}</span>
											<span class="info-img like"><i class="layui-icon layui-icon-praise"></i>{{ $comment['like'] }}</span>
										</p>
										<p class="info-intr">{{ $comment['content'] }}</p>
									</div>
								</div>
							@endforeach
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
