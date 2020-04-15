/**
 * @author : liuxinhui
 * @email : liuxinhui@haiwang.link
 * @time : 2019/11/6 17:26
 * @company : 深圳海王集团股份有限公司
 * @web : https://www.neptunus.com
 */

class InitUeditor
{
	constructor() {

	}

	//获取ueditor 编辑器少量配置按钮
	loadUeditorSmall(textId, frameWidth, frameHeight)
	{
		var ueSmall = UE.getEditor(textId,
			{
				initialFrameWidth:frameWidth,
				initialFrameHeight:frameHeight,
				autoFloatEnabled:false,
				toolbars: [
					['fullscreen', 'source', 'undo', 'redo', 'bold'],
					['bold', 'italic', 'underline', 'insertimage','fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc']
				],
				zIndex: 0,
				wordCount:false,
				autoHeightEnabled:false,
			});
	}

	loadUeditorBig(textId, frameWidth, frameHeight)
	{
		var ueBig = UE.getEditor(textId,
			{
				initialFrameWidth:frameWidth,
				initialFrameHeight:frameHeight,
				autoFloatEnabled:false,
				toolbars:[
					['fullscreen', 'undo', 'redo', '|',
						'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', '|',
						'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
						'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
						'indent', 'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|',
						'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
						'simpleupload', 'insertimage', 'insertvideo', 'map', 'horizontal', 'date', 'time', 'spechars', 'inserttable', 'preview']
				],
				zIndex: 0,
				wordCount:false,
				autoHeightEnabled:false,
			});
	}
}
