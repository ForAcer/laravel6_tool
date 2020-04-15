@extends('layouts.common')
@section('content')
    <div id="LAY_app">
        <div class="layui-layout layui-layout-admin">
            <div class="layui-header" style="background: #065590;">
                <!-- 头部区域 -->
                <ul class="layui-nav layui-layout-left">
                    <li class="layui-nav-item layadmin-flexible" lay-unselect>
                        <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
                            <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
                        </a>
                    </li>
                    <li class="layui-nav-item" lay-unselect>
                        <a href="javascript:;" layadmin-event="refresh" title="刷新">
                            <i class="layui-icon layui-icon-refresh-3"></i>刷新当前窗口
                        </a>
                    </li>
                </ul>
                <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">

                    <li class="layui-nav-item layui-hide-xs" lay-unselect>
                        <a lay-href="{{route('admin.menu.index')}}">
                            <i class="layui-icon layui-icon-more"></i>菜单管理
                        </a>
                    </li>
                    <li class="layui-nav-item layui-hide-xs" lay-unselect>
                        <a href="javascript:;" id="clearcache-btn" layadmin-event="clearcache">
                            <i class="layui-icon layui-icon-delete"></i>清理缓存
                        </a>
                    </li>
                    <li class="layui-nav-item layui-hide-xs" lay-unselect>
                        <a href="javascript:;" layadmin-event="fullscreen">
                            <i class="layui-icon layui-icon-screen-full"></i>全屏显示
                        </a>
                    </li>
                    <li class="layui-nav-item" lay-unselect>
                        <a href="javascript:;">
                            <cite>{{$adminInfo['name']}}</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd><a lay-href="{{route('admin.system.changeinfo')}}">修改信息</a></dd>
                            <hr>
                            <dd style="text-align: center;"><a layadmin-event="logout-system" id="logout-system" data-href="{{route('admin.logout')}}">退出</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item layui-hide-xs" lay-unselect>
                        <a href="javascript:;" layadmin-event="about"><i class="layui-icon"></i></a>
                    </li>
                    <li class="layui-nav-item layui-show-xs-inline-block layui-hide-sm" lay-unselect>
                        <a href="javascript:;" layadmin-event="more"></a>
                    </li>
                </ul>
            </div>

            <!-- 侧边菜单 -->
            <div class="layui-side layui-side-menu" style="background: #222d32 !important;">
                <div class="layui-side-scroll">
                    <div class="layui-logo" style="padding:0px;background: none;background: #065590 !important;">
                        <a href="{{route('admin.main.index')}}">管理系统</a>
                    </div>

                    <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu" lay-filter="layadmin-system-side-menu">
                        @if(!empty($menuList) && count($menuList) > 0)
                            @foreach($menuList as $key => $value)
                                <li data-name="component" class="layui-nav-item">
                                    @if($value->id == 72)
                                        <a lay-href="{{route($value->url_as)}}" class="layui-this" lay-tips="{{$value->name}}" lay-direction="2">
                                            @else
                                                <a href="javascript:;" lay-tips="{{$value->name}}" lay-direction="2">
                                                    @endif
                                                    <i class="layui-icon {{$value->icon_text}}"></i>
                                                    <cite>{{$value->name}}</cite>
                                                </a>
                                                @if(!empty($value->children))
                                                    <dl class="layui-nav-child">
                                                        @foreach($value->children as $v)
                                                            <dd data-name="button">
                                                                @if(!empty($v->children))
                                                                    <a href="javascript:;">{{$v->name}}</a>
                                                                    <dl class="layui-nav-child">
                                                                        @foreach($v->children as $sv)
                                                                            <dd data-name="list"><a lay-href="{{route($sv->url_as)}}">{{$sv->name}}</a></dd>
                                                                        @endforeach
                                                                    </dl>
                                                                @else
                                                                    <a lay-href="{{route($v->url_as)}}">{{$v->name}}</a>
                                                                @endif
                                                            </dd>
                                                        @endforeach
                                                    </dl>
                                    @endif
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            <!-- 页面标签 -->
            <div class="layadmin-pagetabs" id="LAY_app_tabs">
                <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
                <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
                <div class="layui-icon layadmin-tabs-control layui-icon-down">
                    <ul class="layui-nav layadmin-tabs-select" lay-filter="layadmin-pagetabs-nav">
                        <li class="layui-nav-item" lay-unselect>
                            <a href="javascript:;"></a>
                            <dl class="layui-nav-child layui-anim-fadein">
                                <dd layadmin-event="closeThisTabs"><a href="javascript:;">关闭当前标签页</a></dd>
                                <dd layadmin-event="closeOtherTabs"><a href="javascript:;">关闭其它标签页</a></dd>
                                <dd layadmin-event="closeAllTabs"><a href="javascript:;">关闭全部标签页</a></dd>
                            </dl>
                        </li>
                    </ul>
                </div>
                <div class="layui-tab" lay-unauto lay-allowClose="true" lay-filter="layadmin-layout-tabs">
                    <ul class="layui-tab-title" id="LAY_app_tabsheader">
                        <li lay-id="{{route('admin.main.home')}}" lay-attr="{{route('admin.main.home')}}" class="layui-this"><i class="layui-icon layui-icon-home"></i></li>
                    </ul>
                </div>
            </div>


            <!-- 主体内容,首页内容显示 -->
            <div class="layui-body" id="LAY_app_body">
                <div class="layadmin-tabsbody-item layui-show">
                    <iframe src="{{route('admin.main.home')}}" frameborder="0" class="layadmin-iframe"></iframe>
                </div>
            </div>

            <div class="layadmin-body-shade" layadmin-event="shade"></div>
        </div>
    </div>
    <script>
		const csrf_token = '{{csrf_token()}}';
		layui.config({
			base: BASE_URL //静态资源所在路径
		}).extend({
			index: 'lib/index' //主入口模块
		}).use('index', function()
		{
			var $ = layui.$;
			$('#logout-system').on('click', function()
			{
				let request_url = "{{route('admin.logout')}}";
				layer.confirm('是否退出?', function(index){
					hwObj.AjaxCommon($, request_url, '','post', csrf_token, '', function () {
                        setTimeout(function () {
                            window.location.href = "{{route('admin.login')}}";
						}, 1200)
					})
				});
			});

			$('#clearcache-btn').on('click', function()
			{
				let request_url = "{{route('admin.clear.cache')}}";
				hwObj.AjaxCommon($, request_url, '','post', csrf_token)
			});
		});
    </script>
@endsection