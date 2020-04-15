@extends('layouts.common')
@section('content')
	<div class="layui-fluid">
		<div class="layui-row layui-col-space15" style="background: #fafafa;">
			<div class="layui-col-md12">
				<div class="layui-card">
					<div class="layui-card-body" style="padding:0px;">
						<table class="layui-hide" lay-size="sm" id="group-table-operate" lay-filter="group-table-operate"></table>
					</div>
				</div>
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
				,url: "{{route('admin.system.logslist')}}"
				,cols: [[
					{field:'id', minWidth:50, maxWidth:80, title: 'ID'}
					,{field:'admin_name', minWidth:120,maxWidth:150, title: '管理员'}
					,{field:'content',minWidth:200, maxWidth:400, title: '内容'}
					,{field:'from_ip',minWidth:100, maxWidth:150, title: '来源IP'}
					,{field:'ctime_text',minWidth:100, maxWidth:150, title: '创建时间'}
				]]
				,page:true
				,limit:15
				,limits: [15, 30, 45]
			});
		});
	</script>
@endsection