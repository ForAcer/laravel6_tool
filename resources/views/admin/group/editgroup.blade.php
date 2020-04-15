@extends('layouts.common')
@section('content')
	<div class="layui-fluid">
		<div class="layui-row layui-col-space15" style="background: #fafafa;height:auto;">
			<div class="layui-col-md12">
				<div class="layui-card">
					<div class="layui-card-header">
						<p class="pull-left layui-col-sm10">@if(!empty($groupInfo)) 编辑@else 新增@endif角色</p>
					</div>
					<div class="layui-card-body">
						<form class="layui-form">
							@if(!empty($groupInfo))
								<input type="hidden" value="{{$groupInfo->id}}" id="groupId" name="groupId" />
							@else
								<input type="hidden" value="" id="groupId" name="groupId" />
							@endif
							<div class="layui-row layui-col-space10 layui-form-item">
								<div class="layui-col-sm10">
									<label class="layui-form-label">角色名称：</label>
									<div class="layui-input-block">
										<input type="text" id="title" name="title" value="{{!empty($groupInfo->name) ? $groupInfo->name : ''}}" lay-verify="required" placeholder="" autocomplete="off" class="layui-input">
									</div>
								</div>
							</div>
							<div class="layui-form-item layui-col-sm10">
								<label class="layui-form-label">角色描述：</label>
								<div class="layui-input-block">
									<textarea name="desc" id="desc" placeholder="" class="layui-textarea">{{!empty($groupInfo->desc) ? $groupInfo->desc : ''}}</textarea>
								</div>
							</div>

							<div class="layui-form-item">
								<label class="layui-form-label">菜单权限：</label>
								<div class="layui-input-block" style="height:400px;overflow-y: auto;overflow-x: hidden;">
									<div id="tree_menu"></div>
								</div>
							</div>

							<div class="layui-form-item">
								<p class="layui-btn layui-btn-sm layui-btn-normal" lay-submit lay-filter="LAY-user-login-submit">提交保存</p>
								<a href="javascript:void(0);" onclick="history.back(-1);"> <p class="pull-right layui-btn layui-btn-sm layui-btn-primary">返回列表</p></a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<style>
		.layui-layout-body{
			overflow-x:hidden;
			overflow-y: auto;
		}
	</style>
	<script>
		layui.config({
			base: BASE_URL //静态资源所在路径
		}).extend({
			index: 'lib/index' //主入口模块
		}).use(['index', 'form', 'tree'], function(){
			var $ = layui.$,form = layui.form,tree = layui.tree;

			//获取菜单树形结构 并处理成需要的结构
			class GroupDetail{
				constructor(){}
				getTreeData()
				{
					const _this = this;
					let url = "{{route('admin.role.getMenuTreeData')}}";
					$.ajax({
						url: url,
						type: 'get',
						data: {'groupId': $("#groupId").val()},
						dataType: 'json',
						beforeSend: function () {
							this.layerIndex = layer.load(0, {shade: [0.5, '#393D49']});
						},
						success: function (json) {
							if (json.code == 200) {
								let newData = _this.tree_dept_resolved(json.data.tree_data);
								tree.render({
									elem: '#tree_menu'
									,data: newData
									,showCheckbox: true
									,id: 'treeMenuOne'
									,isJump: false
									,click: function(obj){
									}
								});

								let seleteIds = [];
								for(let sv in json.data.seletedids)
								{
									seleteIds.push(json.data.seletedids[sv]);
								}

								tree.setChecked('treeMenuOne', seleteIds); //勾选指定节点
							} else {
								layer.msg("获取数据失败，请重新刷新页面", {icon: 5});//失败的表情
							}
						},
						complete: function () {
							layer.close(this.layerIndex);
						},
					});
				}

				tree_dept_resolved(data)
				{
					const _this = this;
					let newData = data;
					let categorynow = [];
					if(newData != undefined || newData != '')
					{
						categorynow = _this.toJsonArr(newData);
					}

					return categorynow;
				}

				toJsonArr(data)
				{
					const _this = this;
					let jsonObj = [];
					let tmpArr = [];
					$.each(data, function(index, item)
					{
						tmpArr = {
							id:item.id,
							title:item.name,
							parentid:item.parent_id,
							checked:item.checked,
							spread:item.spread
						};

						if(item.children != undefined)
						{
							tmpArr['children'] = _this.toJsonArr(item.children);
						}

						jsonObj.push(tmpArr);
					});

					return jsonObj;
				}
			}

			const groupDetail = new GroupDetail();
			groupDetail.getTreeData();

			//提交
			form.on('submit(LAY-user-login-submit)', function(obj){

				let title = $("#title").val();
				let groupId = $("#groupId").val();
				let desc = $("#desc").val();
				let seletedIds = JSON.stringify(tree.getChecked('treeMenuOne'));

				$.ajax({
					url: "{{route('admin.role.savegroup')}}"
					,data: {
						'groupId' : groupId,
						'title': title,
						'desc' : desc,
						'seletedIds' : seletedIds
					}
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
								window.location.href = "{{route('admin.role.index')}}";
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