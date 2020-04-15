/**
 * @author : liuxinhui
 * @email : liuxinhui@haiwang.link
 * @time : 2019/11/6 16:05
 * @company : 深圳海王集团股份有限公司
 * @web : https://www.neptunus.com
 */

layui.use(['element', 'jquery', 'form', 'laydate', 'tree', 'table', 'upload'], function(){
	const $ = layui.jquery, form = layui.form, laydate = layui.laydate, tree = layui.tree, table = layui.table, upload = layui.upload;
	class EditArticle{
		constructor () {
			this.csrf_token = params.csrf_token;
			this.no_img_url = params.no_img_url;
			this.back_to_url = params.back_to_url;
			this.request_dept_url = params.request_dept_url;
			this.request_cate_url = params.request_cate_url;
			this.ueditorObj = params.ueditorObj;
			this.request_image_url = params.request_image_url;
			this.request_position_url = params.request_position_url;
			this.request_deptlist_url = params.request_deptlist_url;
			this.request_user_url = params.request_user_url;
			this.request_userlist_url = params.request_userlist_url;
			this.upload_file_url = params.upload_file_url;
			this.save_article_url = params.save_article_url;
			this.request_change_status = params.request_change_status;
			this.get_file_url = params.get_file_url;
			this.delete_file_url = params.delete_file_url;
			this.push_selected = params.push_selected;
			this.push_type = params.push_type;
			this.deptIds = [];
			this.positionIds = [];
			this.userIds = [];
			this.fileIds = [];
			if(this.push_type == 3 && this.push_selected != '') {
				for(let dlist of this.push_selected) {
					this.deptIds.push(parseInt(dlist));
				}

				$("#push_selecteds").val(this.deptIds.join(','));
			}

			if(this.push_type == 5 && this.push_selected != '') {
				for(let dlist of this.push_selected) {
					this.positionIds.push(parseInt(dlist));
				}

				$("#push_selecteds").val(this.positionIds.join(','));
			}

			if(this.push_type == 6 && this.push_selected != '') {
				for(let dlist of this.push_selected) {
					this.userIds.push(parseInt(dlist));
				}

				$("#push_selecteds").val(this.userIds.join(','));
			}

			if(params.fileIds != '') {
				for(let flist of params.fileIds) {
					this.fileIds.push(parseInt(flist));
				}

				$("#file_ids").val(this.fileIds.join(','));
			}

		}

		init() {
			const _this = this;
			laydate.render({
				elem: '#str_ctime'
				,type: 'datetime'
			});

			_this.getDeptList();
			_this.getCategoryList();
			_this.ueditorObj.loadUeditorBig('content', 'auto', '500');
			_this.initSelectedBtn();
			_this.initUploadFile();

			$("body").on('click', "#pic_url", function () {
				let domId = $(this).data('domid');
				$.get(_this.request_image_url +'?for_domid='+domId, function(data, status) {
					layer.open({
						type: 1,
						closeBtn: 1,
						offset: '100px',
						anim: 2,
						title:'选择图片',
						shadeClose: false, //开启遮罩关闭
						skin: 'layui-layer-rim', //加上边框
						area: ['520px', '400px'], //宽高
						content: data
					});
				});
			});

			form.on('radio(push_type)', function(data) {
				let push_type = data.value;
				_this.push_type = push_type;
				if(push_type <= 2) {
					$("#dept-area").hide();
					$("#position-area").hide();
					$("#user-area").hide();
					$("#push_selecteds").val('');

				}

				if(push_type == 3) {
					$("#dept-area").show();
					$("#position-area").hide();
					$("#user-area").hide();
					$("#push_selecteds").val(_this.deptIds.join(','));
				}

				if(push_type == 5) {
					$("#dept-area").hide();
					$("#position-area").show();
					$("#user-area").hide();
					$("#push_selecteds").val(_this.positionIds.join(','));
				}

				if(push_type == 6) {
					$("#dept-area").hide();
					$("#position-area").hide();
					$("#user-area").show();
					$("#push_selecteds").val(_this.userIds.join(','));
				}

				return false;
			});

			form.on('submit(save-article-submit)', function(obj) {
				if(!_this.checkFormRequire()) {
					return false;
				}

				obj.field.status = 1;
				$("#status").val(1);
				_this.submitArticle(obj);
			});

			form.on('submit(save-article-submit-backup)', function(obj) {
				if(!_this.checkFormRequire()) {
					return false;
				}

				obj.field.status = 5;
				$("#status").val(5);
				setTimeout(function() {_this.submitArticle(obj);}, 1000);
			});

			$("body").on("click", "#select-postion-list li p i", function() {
				let addValue = $(this).data('positionid');
				let title = $(this).data('title');
				if($.inArray(addValue, _this.positionIds) < 0) {
					let html = '<li data-positionid="'+addValue+'"><p>'+title+'<i title="移除" class="layui-icon layui-icon-delete" data-positionid="'+addValue+'"></i></p></li>';
					$(this).parents('li').addClass('on');
					$(this).remove();
					_this.positionIds.push(addValue);
					$("#selected-position").append(html);
				}

				$("#push_selecteds").val(_this.positionIds.join(','));
				return false;
			});

			$("body").on("click", "#selected-position li p i", function() {
				let removeValue = $(this).data('positionid');
				let positionIds = [];
				for (let plist of _this.positionIds) {
					if(plist != removeValue) {
						positionIds.push(plist);
					}
				}

				$(this).parents('li').remove();
				_this.positionIds = positionIds;
				$("#push_selecteds").val(_this.positionIds.join(','));

			});

			$("body").on("click", "#selected-dept li p i", function() {
				let removeValue = $(this).data('deptid');
				let deptIds = [];
				for (let plist of _this.deptIds) {
					if(plist != removeValue) {
						deptIds.push(plist);
					}
				}

				$(this).parents('li').remove();
				_this.deptIds = deptIds;
				$("#push_selecteds").val(_this.deptIds.join(','));
			});

			$("body").on("click", "#selected-user li p i", function() {
				let removeValue = $(this).data('userid');
				let userIds = [];
				for (let ulist of _this.userIds) {
					if(ulist != removeValue) {
						userIds.push(ulist);
					}
				}

				$(this).parents('li').remove();
				_this.userIds = userIds;
				$("#push_selecteds").val(_this.userIds.join(','));

			});


			$("body").on("click", "#clearSelected", function() {
				$(this).siblings("img").attr('src', _this.no_img_url);
				$(this).siblings("input").val('');
			});

			$("body").on("click", ".audit-article", function() {
				let articleId = $(this).data('id');
				let status = $(this).data('status');
				let audit_note = $("textarea[name='audit_note']").val();
				let msg = '';
				if(status == 2) {
					msg = '确定通过该文章吗，有设置推送的审核通过后推送到指定用户？';
				} else {
					msg = '不通过该文章？';
				}

				let request_url = _this.request_change_status;
				layer.confirm(msg, function(index){
					hwObj.AjaxCommon($, request_url, {'articleId' : articleId, 'status': status, 'audit_note': audit_note}, 'post', _this.csrf_token, index, function () {
						setTimeout(function(){window.location.reload();}, 1200);
					});
				});
			});

			$("body").on("click", ".delete-file-btn", function() {
				let article_id = $(this).data('articleid');
				let res_id = $(this).data('resid');
				layer.confirm('是否删除所选附件？', function(index){
					let fileIds = [];
					for(let flist of _this.fileIds) {
						if(flist != res_id) {
							fileIds.push(flist);
						}
					}

					_this.fileIds = fileIds;
					$("#file_ids").val(_this.fileIds.join(','));
					hwObj.AjaxCommon($, _this.delete_file_url, {'article_id' : article_id, 'file_id': res_id}, 'post', _this.csrf_token, index, function () {
						_this.loadFileTable();
					});
				});
			});
		}

		loadFileTable() {
			const _this = this;
			let article_id = $("#articleId").val();
			hwObj.AjaxCommon($, _this.get_file_url, {'article_id': article_id}, 'get', '', '', function () {
				$("body").on("click", ".delete-file-btn", function() {
					let article_id = $(this).data('articleid');
					let res_id = $(this).data('resid');
					layer.confirm('是否删除所选附件？', function(index){
						hwObj.AjaxCommon($, _this.delete_file_url, {'article_id' : article_id, 'file_id': res_id}, 'post', _this.csrf_token, index, function () {
							_this.loadFileTable();
						});
					});
				});
			}, 'filesList', 'html')
		}

		checkFormRequire() {
			const _this = this;
			let categoryId = $("#categoryId").val();
			if(categoryId == '') {
				layer.msg('请选择所属分类', {icon:5});
				return false;
			}

			let deptId = $("#deptId").val();
			if(deptId == '') {
				layer.msg('请选择所属部门', {icon:5});
				return false;
			}

			let note = $("#note").val();
			if(note == '') {
				layer.msg('请填写简介', {icon:5});
				return false;
			}

			// let pic_url = $("input[name='pic_url']").val();
			// if(pic_url == '') {
			// 	layer.msg('请选择图片', {icon:5});
			// 	return false;
			// }

			//_this.setHiddenValue();
			return true;
		}

		setHiddenValue() {
			const _this = this;
			let push_type_new = parseInt($("input[name='push_type']:checked").val());
			switch (push_type_new){
				case 1:;break;
				case 2:;break;
				case 3:let deptStr = _this.deptIds.join(',');$("#push_selecteds").val(deptStr);break;
				case 5:let positionStr = _this.positionIds.join(',');$("#push_selecteds").val(positionStr);break;
				case 6:let userStr = _this.userIds.join(','); $("#push_selecteds").val(userStr);break;
				default:;
			}

			if(_this.fileIds.length > 0){
				let fileStr = _this.fileIds.join(',');
				$("#file_ids").val(fileStr);
			}
		}

		submitArticle(formObj) {
			const _this = this;
			layer.load(1);
			$.ajax({
				url: _this.save_article_url
				,data: formObj.field
				,type:'post'
				,dataType:'json'
				,headers:{
					'X-CSRF-TOKEN': _this.csrf_token
				}
				,success: function(res) {
					layer.closeAll();
					if(res.code == 200) {
						layer.msg(res.msg, {icon: 1});
						setInterval(function(){
							window.location.href = _this.back_to_url;
						}, 1200);
					}
					else {
						layer.msg(res.msg, {icon: 5});
						return false;
					}
				}
				,error:function() {
					layer.closeAll();
					layer.msg('服务器请求失败，请刷新页面重试');
					return false;
				}
			});
			;
		}

		getDeptList() {
			const _this = this;
			let url = _this.request_dept_url;
			$.ajax({
				url: url,
				type: 'get',
				data: {},
				dataType: 'json',
				success: function (json) {
					if (json.code == 200) {
						let deptList =  _this.jsonToArr(json.data);
						_this.initDeptSelected(deptList);
					}
					else {
						layer.msg("获取数据失败，请重新刷新页面", {icon: 5});//失败的表情
					}
				},
				complete: function () {
				},
			});
		}

		jsonToArr(data) {
			const _this = this;
			let jsonObj = [];
			let tmpArr = [];
			$.each(data, function(index, item)
			{
				tmpArr = {
					id:item.id,title:item.title,parentid:item.parentid,spread:item.spread
				}
				if(item.children != undefined)
				{
					tmpArr['children'] = _this.jsonToArr(item.children);
				}

				jsonObj.push(tmpArr);
			});

			return jsonObj;
		}

		initDeptSelected(deptData) {
			tree.render({
				elem:"#deptTree"
				,onlyIconControl:true
				,data:deptData
				,click:function(node) {
					$("#select_dept").text(node.data.title);
					$("#deptId").val(node.data.id);
				}
			});

			$("#deptSelected").children(".layui-select-title").on("click",function(e) {
				$(".layui-form-select").not($(this).parents(".layui-form-select")).removeClass("layui-form-selected");
				$(this).parents("#deptSelected").toggleClass("layui-form-selected");
				layui.stope(e);
			}).on("click","dl i",function(e) {
				layui.stope(e);
			});

			$(document).on("click",function(e) {
				$("#deptSelected").removeClass("layui-form-selected");
			});
		}

		getCategoryList() {
			const _this = this;
			let url = _this.request_cate_url;
			$.ajax({
				url: url,
				type: 'get',
				data: {},
				dataType: 'json',
				success: function (json) {
					if (json.code == 200) {
						let cateList =  _this.jsonToArr(json.data);
						_this.initCategorySelected(cateList);
					}
					else {
						layer.msg("获取数据失败，请重新刷新页面", {icon: 5});//失败的表情
					}
				},
				complete: function () {
				},
			});
		}

		initCategorySelected(cateData) {
			tree.render({
				elem:"#cateTree"
				,onlyIconControl:true
				,data:cateData
				,click:function(node) {
					$("#select_category").text(node.data.title);
					$("#categoryId").val(node.data.id);
				}
			});

			$("#cateSelected").children(".layui-select-title").on("click",function(e) {
				$(".layui-form-select").not($(this).parents(".layui-form-select")).removeClass("layui-form-selected");
				$(this).parents("#cateSelected").toggleClass("layui-form-selected");
				layui.stope(e);
			}).on("click","dl i",function(e) {
				layui.stope(e);
			});

			$(document).on("click",function(e) {
				$("#cateSelected").removeClass("layui-form-selected");
			});
		}

		initSelectedBtn() {
			const _this = this;
			$("body").on("click", ".selected-btn", function() {
				let btn_type = $(this).data('type');
				if(btn_type == '' || btn_type == undefined) {
					return false;
				}

				switch (btn_type) {
					case 'dept':_this.openSelectedArea('dept');break;
					case 'position':_this.openSelectedArea('position');break;
					case 'user':_this.openSelectedArea('user');break;
					default:;
				}
			});
		}

		openSelectedArea(type) {
			const _this = this;
			if(type == 'position') {
				hwObj.layerOpen($, _this.request_position_url+'?selected='+_this.positionIds.join(','), '选择职位', 400, 300, 50, 'yes');
			}

			if(type == 'dept') {
				hwObj.layerOpen($, _this.request_deptlist_url+'?selected='+_this.deptIds.join(','), '选择部门', 400, 500, 50, 'yes', function(){
					_this.initDeptSelectedList();
				});
			}

			if(type == 'user') {
				hwObj.layerOpen($, _this.request_user_url+'?selected='+_this.userIds.join(','), '选择用户', 700, 500, 50, 'no', function() {
					_this.initUserListTable();
					_this.initSearchUserBtn();
				});
			}
		}

		initDeptSelectedList() {
			const _this = this;
			let url = _this.request_dept_url;
			$.ajax({
				url: url,
				type: 'get',
				data: {},
				dataType: 'json',
				success: function (json) {
					if (json.code == 200) {
						let deptList =  _this.jsonToArr(json.data);
						tree.render({
							elem:"#select-dept-tree"
							,data:deptList
							,onlyIconControl:true
							,showLine:true
							,id:"selectDeptTree"
							,click:function(node) {
								let title = node.data.title;
								let addValue = node.data.id;
								if($.inArray(addValue, _this.deptIds) < 0) {
									_this.deptIds.push(addValue);
									let html = '<li data-deptid="'+addValue+'"><p>'+title+'<i title="移除" class="layui-icon layui-icon-delete" data-deptid="'+addValue+'"></i></p></li>';
									$("#selected-dept").append(html);
									$("#push_selecteds").val(_this.deptIds.join(','));
								}
							}
						});
					}
					else {
						layer.msg("获取数据失败，请重新刷新页面", {icon: 5});//失败的表情
					}
				},
				complete: function () {
				},
			});
		}

		initUserListTable() {
			const _this = this;
			table.render({
				elem: '#user-table-operate'
				,url: _this.request_userlist_url
				,id : 'userTable'
				,cols: [[
					{field:'name', title: '姓名'}
					,{align:'center', title: '操作', toolbar: '#user-table-operate-bar'}
				]]
				,page:true
				,limit:20
			});

			table.reload('userTable', {
				page: {
					curr: 1 //重新从第 1 页开始
				}
				,where: {
					selected: _this.userIds.join(',')
				}
			});

			table.on('tool(user-table-operate)', function(obj){
				var data = obj.data;
				if(obj.event === 'add'){
					if($.inArray(data.id, _this.userIds) < 0) {
						_this.userIds.push(data.id);
						let html = '<li data-userid="'+data.id+'"><p>'+data.name+'<i title="移除" class="layui-icon layui-icon-delete" data-userid="'+data.id+'"></i></p></li>';
						$("#selected-user").append(html);
						$("#push_selecteds").val(_this.userIds.join(','));

						$(this).parents("tr").children('td').eq(0).find('div').html(data.name+'<span style="color:#1E9FFF;">(已选)</span>');
					}
				}
				return false;
			});
		}

		initSearchUserBtn() {
			$("body").on('click', '#search-user-btn', function() {
				let username = $("#username").val();
				table.reload('userTable', {
					page: {
						curr: 1 //重新从第 1 页开始
					}
					,where: {
						username:  encodeURI(username)
					}
				});
			});
		}

		initUploadFile() {
			const _this = this;
			var demoListView = $('#fileListTable')
				,uploadListIns = upload.render({
				elem: '#imageList'
				,url: _this.upload_file_url
				,accept: 'file'
				,exts: 'jpg|jpeg|png|gif|txt|doc|docx|ppt|pptx|xls|xlsx|cvs|pdf|rar|zip' //只允许上传压缩文件
				,size: 51200 //限制文件大小，单位 KB
				,multiple: true
				,auto: false
				,bindAction: '#fileListAction'
				,choose: function(obj){
					var files = this.files = obj.pushFile(); //将每次选择的文件追加到文件队列
					//读取本地文件
					obj.preview(function(index, file, result){
						var tr = $(['<tr id="upload-'+ index +'">'
							,'<td>'+ file.name +'</td>'
							,'<td>'+ (file.size/1014).toFixed(1) +'kb</td>'
							,'<td>等待上传</td>'
							,'<td>'
							,'<button class="layui-btn layui-btn-xs image-reload layui-hide">重传</button>'
							,'<button class="layui-btn layui-btn-xs layui-btn-danger image-delete">删除</button>'
							,'</td>'
							,'</tr>'].join(''));

						//单个重传
						tr.find('.image-reload').on('click', function(){
							obj.upload(index, file);
						});

						//删除
						tr.find('.image-delete').on('click', function(){
							delete files[index]; //删除对应的文件
							tr.remove();
							uploadListIns.config.elem.next()[0].value = ''; //清空 input file 值，以免删除后出现同名文件不可选
						});

						demoListView.append(tr);
					});
				}
				,done: function(res, index, upload){
					if(res.code == 200){ //上传成功
						var tr = demoListView.find('tr#upload-'+ index)
							,tds = tr.children();
						tds.eq(2).html('<span style="color: #5FB878;">'+res.msg+'</span>');
						tds.eq(3).html(''); //清空操作


						_this.fileIds.push(res.data.res_id);
						$("#file_ids").val(_this.fileIds.join(','))
						//setTimeout(function(){tds.remove();}, 1200);
						return delete this.files[index]; //删除文件队列已经上传成功的文件
					}

					this.error(index, upload, res);
				}
				,error: function(index, upload, res){
					var tr = demoListView.find('tr#upload-'+ index)
						,tds = tr.children();
					tds.eq(2).html('<span style="color: #FF5722;">'+res.msg+'</span>');
					tds.eq(3).find('.image-reload').removeClass('layui-hide'); //显示重传
					//setTimeout(function(){return delete this.files[index];}, 1200);
				}
			});
		}
	}

	const editArticleObj = new EditArticle(params);
	editArticleObj.init();
});