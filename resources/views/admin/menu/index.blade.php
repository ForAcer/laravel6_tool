@extends('layouts.common')
@section('content')
    <div class="layui-fluid" style="background: #fff;overflow:hidden;">
		<?php
		$admin_info = session()->get('pc_admin_info');
		$admin_menu_list = session()->get('admin_menu_'.$admin_info['id']);
		if(in_array('admin.menu.addmenu', $admin_menu_list)){
		?>
        <div style="padding-bottom: 10px;">
            <a href="{{route('admin.menu.editmenu')}}"><button class="layui-btn layui-btn-normal layui-btn-sm layuiadmin-btn-list"><i class="layui-icon layui-icon-add-1"></i>添加菜单</button></a>
        </div>
		<?php } ?>
        <table class="layui-table" lay-size="sm" style="margin:0px;">
            <thead>
            <tr>
                <th>ID</th>
                <th>名称</th>
                <th>路由别名</th>
                <th>菜单显示</th>
                <th>排序</th>
                <th>创建时间</th>
                <th>是否启用</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @if(!empty($tree_menu) && count($tree_menu) > 0)
                @foreach($tree_menu as $key => $value)
                    <tr id="tr_{{$value->id}}">
                        <td>{{$value->id}}</td>
                        <td><p>{{$value->name}}</p></td>
                        <td>{{$value->url_as}}</td>
                        <td>@if($value->is_display == 1)  显示 @else <p style="color:red;">不显示</p>@endif</td>
                        <td>{{$value->sort}}</td>
                        <td>{{date('Y-m-d H:i:s', $value->ctime)}}</td>
                        <td>@if($value->status == 1)  启用 @else <p style="color:red;">不启用</p>@endif</td>
                        <td>
                            <div class="layui-table-cell" id="table-content-list">
								<?php
								if(in_array('admin.menu.editmenu', $admin_menu_list)){
								?>
                                <a class="layui-btn layui-btn-normal layui-btn-xs" href="{{route('admin.menu.editmenu', ['mid' => $value->id ])}}"><i class="layui-icon layui-icon-edit"></i>编辑</a>
								<?php }
								if(in_array('admin.menu.deletemenu', $admin_menu_list)){
								?>
                                <a class="layui-btn layui-btn-danger layui-btn-xs layer-event-delete" data-mid="{{$value->id}}" lay-event="del"><i class="layui-icon layui-icon-delete"></i>删除</a>
								<?php }?>
                            </div>
                        </td>
                    </tr>
                    @if(!empty($value->children))
                        @foreach($value->children as $ckey => $v)
                            <tr id="tr_{{$v->id}}">
                                <td>{{$v->id}}</td>
                                <td><strong style="float:left;">|—</strong><p style="float:left;">{{$v->name}}</p></td>
                                <td>{{$v->url_as}}</td>
                                <td>@if($v->is_display == 1)  显示 @else <p style="color:red;">不显示</p>@endif</td>
                                <td>{{$v->sort}}</td>
                                <td>{{date('Y-m-d H:i:s', $v->ctime)}}</td>
                                <td>@if($v->status == 1)  启用 @else <p style="color:red;">不启用</p>@endif</td>
                                <td>
                                    <div class="layui-table-cell" id="table-content-list">
										<?php
										if(in_array('admin.menu.editmenu', $admin_menu_list)){
										?>
                                        <a class="layui-btn layui-btn-normal layui-btn-xs" href="{{route('admin.menu.editmenu', ['mid' => $v->id ])}}"><i class="layui-icon layui-icon-edit"></i>编辑</a>
										<?php }
										if(in_array('admin.menu.deletemenu', $admin_menu_list)){
										?>
                                        <a class="layui-btn layui-btn-danger layui-btn-xs layer-event-delete" data-mid="{{$v->id}}" lay-event="del"><i class="layui-icon layui-icon-delete"></i>删除</a>
										<?php }?>
                                    </div>
                                </td>
                            </tr>
                            @if(!empty($v->children))
                                @foreach($v->children as $skey => $sv)
                                    <tr id="tr_{{$sv->id}}">
                                        <td>{{$sv->id}}</td>
                                        <td><strong style="float:left;">|——</strong><p style="float:left;">{{$sv->name}}</p></td>
                                        <td>{{$sv->url_as}}</td>
                                        <td>@if($sv->is_display == 1)  显示 @else <p style="color:red;">不显示</p>@endif</td>
                                        <td>{{$sv->sort}}</td>
                                        <td>{{date('Y-m-d H:i:s', $sv->ctime)}}</td>
                                        <td>@if($sv->status == 1)  启用 @else <p style="color:red;">不启用</p>@endif</td>
                                        <td>
                                            <div class="layui-table-cell" id="table-content-list">
												<?php
												if(in_array('admin.menu.editmenu', $admin_menu_list)){
												?>
                                                <a class="layui-btn layui-btn-normal layui-btn-xs" href="{{route('admin.menu.editmenu', ['mid' => $sv->id ])}}"><i class="layui-icon layui-icon-edit"></i>编辑</a>
												<?php }
												if(in_array('admin.menu.deletemenu', $admin_menu_list)){
												?>
                                                <a class="layui-btn layui-btn-danger layui-btn-xs layer-event-delete" data-mid="{{$sv->id}}" lay-event="del"><i class="layui-icon layui-icon-delete"></i>删除</a>
												<?php }?>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <script>
		layui.config({
			base: BASE_URL //静态资源所在路径
		}).extend({
			index: 'lib/index' //主入口模块
		}).use(['layer'], function(){
			const $ = layui.$;
			$(".layer-event-delete").off("click").on("click", function(){
				let mid = $(this).data('mid');
				layer.confirm('是否要删除本菜单?', function(index){
					$.ajax({
						url: "{{route('admin.menu.deletemenu')}}",
						type:'post',
						dataType:'json',
						data:{'mid' : mid},
						headers:{
							'X-CSRF-TOKEN': '{{ csrf_token() }}'
						},
						success:function (json) {
							if(json.code == 200)
							{
								layer.msg(json.msg, {icon: 1});
								$("#tr_"+mid).remove();
							}
							else
							{
								layer.msg(json.msg, {icon: 5});
							}
						},
						error:function () {
							layer.msg("服务器请求失败，请刷新页面重试！", {icon: 5});
						}
					});
					layer.close(index);
				});
			});
		});
    </script>
@endsection