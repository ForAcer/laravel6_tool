/**
 * @author : liuxinhui
 * @email : liuxinhui@haiwang.link
 * @time : 2019/10/31 13:51
 * @company : 深圳海王集团股份有限公司
 * @web : https://www.neptunus.com
 */

class HW{
	constructor() {

	}

	AjaxCommon($, url, data, requestType, csrf_token, index = '', callback = '', showId = '', dataType = 'json', action = '') {
		$.ajax({
			url: url,
			type:requestType,
			dataType:dataType,
			headers:{
				'X-CSRF-TOKEN' : csrf_token
			},
			data:data,
			success:function(json){
				if(showId != '' && requestType == 'get' && dataType == 'html') {
					if(action == 'add') {
						$("#"+showId).append(json);
					} else {
						$("#"+showId).empty('').html(json);
					}

					if(callback != '') {
						callback();
					}

					return false;
				}
				else {
					if(json.code == 200) {
						layer.msg(json.msg, { icon: 1});
						if(callback != '') {
							callback();
							return false;
						}
					}
					else {
						layer.msg(json.msg, { icon: 5});
					}

					if(index != '') {
						layer.close(index);
					}

					return false;
				}
			},
			error:function (json) {
				layer.msg('服务器请求失败，请刷新重试！', { icon: 5});
				if(index != '')
				{
					layer.close(index);
				}

				return false;
			}
		});
	}

	layerOpen($, url, title, width = 400, height = 250, offset = 50, needYes = 'no', callback) {
		if (needYes == 'yes') {
			$.get(url, function(data, status) {
				layer.open({
					type : 1,
					closeBtn : 1,
					offset : offset + 'px',
					anim : 2,
					title : title,
					shadeClose : false, //开启遮罩关闭
					skin: 'layui-layer-rim', //加上边框
					area: [width + 'px', height + 'px'], //宽高
					content: data,
					success: function(layero, index){
						if(callback != '' && callback instanceof Function) {
							callback();
						}
					},
					yes: function(index, layero){
						layer.close(index); //如果设定了yes回调，需进行手工关闭
					}
				});
			});
		} else if(callback != '' && callback instanceof Function && needYes == 'no') {
			$.get(url, function(data, status) {
				layer.open({
					type : 1,
					closeBtn : 1,
					offset : offset + 'px',
					anim : 2,
					title : title,
					shadeClose : false, //开启遮罩关闭
					skin: 'layui-layer-rim', //加上边框
					area: [width + 'px', height + 'px'], //宽高
					content: data,
					success: function(layero, index){
						callback();
					}
				});
			});
		} else {
			$.get(url, function(data, status) {
				layer.open({
					type : 1,
					closeBtn : 1,
					offset : offset + 'px',
					anim : 2,
					title : title,
					shadeClose : false, //开启遮罩关闭
					skin: 'layui-layer-rim', //加上边框
					area: [width + 'px', height + 'px'], //宽高
					content: data
				});
			});
		}
	}

	layuiTable() {

	}

	layuiTree() {

	}

	ajaxLoad($, ajaxAreaId = '') {
		let html = '<div style="text-align: center;margin:20px 0px;"><i style="font-size:26px;" class="layui-icon layui-icon-loading layui-icon layui-anim layui-anim-rotate layui-anim-loop"></i></div>';
		$("#"+ajaxAreaId).empty().html(html);
	}

	removeAjaxLoad($, ajaxAreaId = '') {
		$("#"+ajaxAreaId).empty();
	}
}