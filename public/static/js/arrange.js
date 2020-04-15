/**
 * @author : liuxinhui
 * @email : liuxinhui@haiwang.link
 * @time : 2020/1/6 11:26
 * @company : 深圳海王集团股份有限公司
 * @web : https://www.neptunus.com
 */

layui.config({
	base: SITE_URL + '/static/hradmin/'
}).extend({
	index: 'lib/index'
}).use(['index', 'table', 'jquery', 'laytpl'], function() {
	const $ = layui.jquery, table = layui.table, laytpl = layui.laytpl;
	class Arrange {
		constructor() {

		}

		init() {
			const _this = this;
			_this.initTable();

			laytpl.config({
				open: '<%',
				close: '%>'
			});
		}

		initTable() {
			table.render({
				elem: '#group-table-operate'
				,url: params.get_arrange_list_url
				,id: "user-table-operate"
				,cols: [[
					{field:'title', title: '标题'}
					,{field:'type_text', width:100,title: '类型'}
					,{field:'user_name', width:150,title: '发布人'}
					,{field:'begin_text', width:200, title: '开始时间'}
					,{field:'end_text', width:200, title: '结束时间'}
					,{field:'level_text', width:100,title: '状态'}
					,{field:'ctime_text', width:200, title: '发布时间'}
					,{align:'center', width:200, title: '操作', toolbar: '#group-table-operate-bar'}
				]]
				,page:true
				,limit:15
				,limits:[15, 30, 45]
			});
		}

		reloadTable() {
			table.reload('user-table-operate', {
				page: {
					curr: 1
				}});
		}
	}

	const arrangeObj = new Arrange();
	arrangeObj.init();

	//监听工具条
	table.on('tool(group-table-operate)', function(obj){
		var data = obj.data;
		if(obj.event === 'del'){
			layer.confirm('确定删除该日程吗？', function(index){
				hwObj.AjaxCommon($, params.del_arrange_url, {'aid' : data.id}, 'post', csrf_token, index, function(){
					setTimeout(function(){arrangeObj.reloadTable()}, 1100);
				});
			});
		} else if(obj.event === 'edit'){
			window.location.href = params.edit_arrange_url+'?aid='+data.id;
		}
	});
});