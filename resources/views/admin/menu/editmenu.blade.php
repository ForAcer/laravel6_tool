@extends('layouts.common')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15" style="background: #fafafa;">
            <div class="layui-col-sm12">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <p class="pull-left layui-col-sm10">@if(!empty($menuInfo)) 编辑@else 新增@endif菜单</p>
                    </div>
                    <div class="layui-card-body">
                        <form class="layui-form">
                            <div class="layui-row layui-col-space10 layui-form-item">
                                @if(!empty($menuInfo))
                                    <input type="hidden" value="{{$menuInfo->id}}" name="mid" />
                                @else
                                    <input type="hidden" value="" name="mid" />
                                @endif
                                <div class="layui-row layui-col-space10 layui-form-item">
                                    <div class="layui-col-sm10">
                                        <label class="layui-form-label">菜单名称：</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="title" value="{{!empty($menuInfo->name) ? $menuInfo->name : ''}}" lay-verify="required" placeholder="" autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-row layui-col-space10 layui-form-item">
                                    <div class="layui-col-sm10">
                                        <label class="layui-form-label">上级菜单：</label>
                                        <div class="layui-input-block">
                                            <select name="parent_id" lay-verify="required">
                                                <option value="0">顶级菜单</option>
                                                @if(!empty($tree_menu) && count($tree_menu) > 0)
                                                    @foreach($tree_menu as $cv)
                                                        <option @if(!empty($menuInfo) && $cv->id == $menuInfo->parent_id) selected @endif value="{{$cv->id}}">{{$cv->name}}</option>
                                                        @if(!empty($cv->children) && count($cv->children) > 0)
                                                            @foreach($cv->children as $csv)
                                                                <option @if(!empty($menuInfo) && $csv->id == $menuInfo->parent_id) selected @endif value="{{$csv->id}}"> &nbsp;&nbsp; ➥ {{$csv->name}}</option>
                                                                @if(!empty($csv->children) && count($csv->children) > 0)
                                                                    @foreach($csv->children as $csvl)
                                                                        <option @if(!empty($menuInfo) && $csvl->id == $menuInfo->parent_id) selected @endif value="{{$csvl->id}}"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ➥ {{$csvl->name}}</option>
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="layui-form-item layui-col-sm10">
                                    <label class="layui-form-label">菜单描述：</label>
                                    <div class="layui-input-block">
                                        <textarea name="desc" placeholder="" class="layui-textarea">{{!empty($menuInfo->desc) ? $menuInfo->desc : ''}}</textarea>
                                    </div>
                                </div>

                                <div class="layui-row layui-col-space10 layui-form-item">
                                    <div class="layui-col-sm10">
                                        <label class="layui-form-label">路由名称：</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="url_as" value="{{!empty($menuInfo->url_as) ? $menuInfo->url_as : ''}}" lay-verify="required" placeholder="" autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                </div>

                                <div class="layui-row layui-col-space10 layui-form-item">
                                    <div class="layui-col-sm10">
                                        <label class="layui-form-label">icon图标：</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="icon_text" value="{{!empty($menuInfo->icon_text) ? $menuInfo->icon_text : ''}}" placeholder="" autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                </div>

                                <div class="layui-row layui-col-space10 layui-form-item">
                                    <div class="layui-col-sm10">
                                        <label class="layui-form-label">排序：</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="sort" value="{{!empty($menuInfo->sort) ? intval($menuInfo->sort) : 0}}" lay-verify="required" placeholder="默认为0,越大越排在前面" autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label">是否显示：</label>
                                    <div class="layui-input-block">
                                        <input type="radio" @if(!empty($menuInfo) && $menuInfo->is_display == 1 || empty($menuInfo)) checked @endif name="is_display" value="1" title="显示" />
                                        <input type="radio" @if(!empty($menuInfo) && $menuInfo->is_display == 2) checked @endif name="is_display" value="2" title="不显示" />
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label">是否启用：</label>
                                    <div class="layui-input-block">
                                        <input type="radio" @if(!empty($menuInfo) && $menuInfo->status == 1 || empty($menuInfo)) checked @endif name="status" value="1" title="启用" />
                                        <input type="radio" @if(!empty($menuInfo) && $menuInfo->status == 2) checked @endif name="status" value="2" title="不启用" />
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <p class="layui-btn layui-btn-sm layui-btn-normal" lay-submit lay-filter="LAY-user-login-submit">提交保存</p>
                                    <a href="javascript:void(0);" onclick="history.back(-1);"> <p class="pull-right layui-btn layui-btn-sm layui-btn-primary">返回列表</p></a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .layui-form-item{margin-bottom:0px;}
    </style>
    <script>
		layui.config({
			base: BASE_URL //静态资源所在路径
		}).extend({
			index: 'lib/index' //主入口模块
		}).use(['index', 'form'], function(){
			var $ = layui.$,form = layui.form;

			//提交
			form.on('submit(LAY-user-login-submit)', function(obj){
				$.ajax({
					url: SITE_URL + '/hradmin/menu/savemenu'
					,data: obj.field
					,type:'post'
					,dataType:'json'
					,headers:{
						'X-CSRF-TOKEN': '{{csrf_token()}}'
					}
					,success: function(res){
						if(res.code == 200)
						{
							layer.msg(res.msg, {icon: 1});
							setInterval(function(){
								window.location.href = SITE_URL + '/hradmin/menu/index';
							}, 1000);
						}
						else
						{
							layer.msg(res.msg, {icon: 5});
							return false;
						}
					}
					,error:function(){
						//刷新验证码
						layer.msg('服务器请求失败，请刷新页面重试');
						return false;
					}
				});
			});
		});
    </script>
@endsection