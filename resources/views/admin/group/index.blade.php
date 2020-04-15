@extends('layouts.common')
@section('content')
	<?php
	$adminInfo = session()->get('pc_admin_info');
	$admin_menu_list = session()->get('admin_menu_'.$adminInfo['id']);
	?>
    <div class="layui-fluid" style="background: #fff;overflow:hidden;">
                <div class="layui-card">
	                <?php if(in_array('admin.role.addgroup', $admin_menu_list)){ ?>
                    <div class="layui-card-header" style="padding:0px;">
                        <a href="{{route('admin.role.editgroup')}}"><button class="layui-btn layui-btn-normal layui-btn-sm layuiadmin-btn-list"><i class="layui-icon layui-icon-add-1"></i>添加角色</button></a>
                    </div>
					<?php } ?>
                    <div class="layui-card-body" style="padding:0px;">
                        <table class="layui-hide" lay-size="sm" id="group-table-operate" lay-filter="group-table-operate"></table>
                        <script type="text/html" id="group-table-operate-bar">
	                        <?php if(in_array('admin.role.editgroup', $admin_menu_list)){ ?>
                            <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">编辑</a>
	                        <?php } ?>

	                        <?php if(in_array('admin.role.delgroup', $admin_menu_list)){ ?>
                            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
	                        <?php } ?>

						</script>
                    </div>
                </div>
    </div>
    <script>
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
				,url: "{{route('admin.role.grouplist')}}"
				,cols: [[
					{field:'id', title: 'ID'}
					,{field:'name', title: '角色名称'}
					,{field:'desc', title: '角色描述'}
					,{field:'status_text', title: '角色状态'}
					,{field:'ctime_text', title: '创建时间'}
					,{align:'center', title: '操作', toolbar: '#group-table-operate-bar'}
				]]
				,page:true
			});

			class Group{
				constructor(){

				}

				deleteGroup(groupId, index){
					$.ajax({
						url: '{{route('admin.role.deletegroup')}}',
						type: 'post',
						data: {'groupId':groupId},
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

				editGroup(groupId){
                    window.location.href = '{{route('admin.role.editgroup')}}?groupId='+groupId;
				}
			}

			const groupObj = new Group();

			//监听工具条
			table.on('tool(group-table-operate)', function(obj){
				var data = obj.data;
				if(obj.event === 'del'){
					layer.confirm('确定删除对应的角色吗？', function(index){
						if(data.id == 1)
						{
							layer.msg('超级管理员组不可以删除', { icon: 5});
							return false;
						}

						groupObj.deleteGroup(data.id, index);
						//执行软删除操作  超级权限组不允许删除

					});
				} else if(obj.event === 'edit'){
                        groupObj.editGroup(data.id);
				}
			});

		});
    </script>
@endsection