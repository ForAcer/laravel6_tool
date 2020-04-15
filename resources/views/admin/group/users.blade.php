@extends('layouts.common')
@section('content')
	<?php
	$adminInfo = session()->get('pc_admin_info');
	$admin_menu_list = session()->get('admin_menu_'.$adminInfo['id']);
	?>
	<div class="layui-fluid" style="background: #fff;overflow:hidden;">
		<div class="layui-card">
			<?php if(in_array('admin.role.addadmins', $admin_menu_list)) { ?>
			<div class="layui-card-header" style="padding:0px;">
				<a href="{{route('admin.role.edituser')}}"><button class="layui-btn layui-btn-normal layui-btn-sm layuiadmin-btn-list"><i class="layui-icon layui-icon-add-1"></i>添加用户</button></a>
			</div>
			<?php } ?>
			<div class="layui-card-body" style="padding:0px;">
				<table class="layui-hide" lay-size="sm" id="group-table-operate" lay-filter="group-table-operate"></table>
				<script type="text/html" id="group-table-operate-bar">
					<?php if(in_array('admin.role.editadmins', $admin_menu_list)) { ?>
					<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">编辑</a>
					<?php } ?>
					<?php if(in_array('admin.role.deladmins', $admin_menu_list)) { ?>
					<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
					<?php } ?>
				</script>
			</div>
		</div>
	</div>
	<script>
		let adminId = '{{$adminInfo['id']}}';
		layui.config({
			base: BASE_URL
		}).extend({
			index: 'lib/index'
		}).use(['index', 'table'], function(){
			var table = layui.table
				,$ = layui.$
				,admin = layui.admin;

			table.render({
				elem: '#group-table-operate'
				,url: "{{route('admin.role.userlist')}}"
				,cols: [[
					{field:'id', width:50, align:'center', title: 'ID'}
					,{field:'group_name', minWidth:150, align:'center', title: '角色'}
					,{field:'username', minWidth:150, align:'center', title: '管理员账号'}
					,{field:'name', minWidth:150, align:'center', title: '姓名'}
					,{field:'is_system_text', minWidth:100, align:'center', title: '是否超管'}
					,{field:'status_text', minWidth:100, align:'center', title: '成员状态'}
					,{field:'ctime_text', minWidth:150, align:'center', title: '创建时间'}
					,{align:'center', minWidth:150, title: '操作', toolbar: '#group-table-operate-bar'}
				]]
				,page:true
			});

			class Group{
				constructor(){

				}

				deleteGroup(adminId, index){
					$.ajax({
						url: '{{route('admin.role.deleteuser')}}',
						type: 'post',
						data: {'adminId':adminId},
						headers:{
							'X-CSRF-TOKEN' : '{{csrf_token()}}'
						},
						dataType: 'json',
						success:function(json){
							if(json.code == 200)
							{
								layer.msg(json.msg, { icon: 1});
								table.reload('group-table-operate', {
									page: {
										curr: 1
									}});
							}
							else
							{
								layer.msg(json.msg, { icon: 5});
							}

							layer.close(index);
							return false;
						},
						error:function(json){
							layer.msg('服务器请求失败，请刷新重试！', { icon: 5});
							layer.close(index);
							return false;
						}
					});
				}

				editGroup(adminId){
					window.location.href = '{{route('admin.role.edituser')}}?adminId='+adminId;
				}
			}

			const groupObj = new Group();

			//监听工具条
			table.on('tool(group-table-operate)', function(obj){
				var data = obj.data;
				if(obj.event === 'del'){
					layer.confirm('确定删除对应的管理员吗？', function(index){
						if(data.id == 1)
						{
							layer.msg('超级管理员不可以删除', { icon: 5});
							return false;
						}

						groupObj.deleteGroup(data.id, index);
						//执行软删除操作  超级权限组不允许删除

					});
				} else if(obj.event === 'edit'){
					if(data.id == 1 && adminId != 1)
					{
						layer.msg('不可编辑超级管理员', { icon: 5});
						return false;
					}
					groupObj.editGroup(data.id);
				}
			});

		});
	</script>
@endsection