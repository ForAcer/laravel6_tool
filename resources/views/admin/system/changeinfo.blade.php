@extends('layouts.common')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15" style="background: #fafafa;">
            <form class="layui-form">
                <div class="layui-row layui-col-space10 layui-form-item">
                    <div class="layui-col-sm12">
                        <label class="layui-form-label">姓名：</label>
                        <div class="layui-input-block">
                            <input type="text" id="username" name="username" value="{{!empty($adminInfo->name) ? $adminInfo->name : ''}}" lay-verify="required" placeholder="" autocomplete="off" class="layui-input" />
                        </div>
                    </div>
                </div>
                <div class="layui-row layui-col-space10 layui-form-item">
                    <div class="layui-col-sm12">
                        <label class="layui-form-label">邮箱：</label>
                        <div class="layui-input-block">
                            <input type="text" id="email" name="email" value="{{!empty($adminInfo->email) ? $adminInfo->email : ''}}"  placeholder="" autocomplete="off" class="layui-input" />
                        </div>
                    </div>
                </div>
                <div class="layui-row layui-col-space10 layui-form-item">
                    <div class="layui-col-sm12">
                        <label class="layui-form-label">描述：</label>
                        <div class="layui-input-block">
                            <input type="text" id="desc" name="desc" value="{{!empty($adminInfo->desc) ? $adminInfo->desc : ''}}" placeholder="" autocomplete="off" class="layui-input" />
                        </div>
                    </div>
                </div>

                <div class="layui-row layui-col-space10 layui-form-item">
                    <div class="layui-col-sm12">
                        <label class="layui-form-label">当前密码：</label>
                        <div class="layui-input-block">
                            <input type="password" id="old_password" name="old_password" value="" placeholder="填写即是修改密码，新密码必填" autocomplete="off" class="layui-input" />
                        </div>
                    </div>
                </div>

                <div class="layui-row layui-col-space10 layui-form-item">
                    <div class="layui-col-sm12">
                        <label class="layui-form-label">新密码：</label>
                        <div class="layui-input-block">
                            <input type="password" id="new_password" name="new_password" value="" placeholder="建议字母和数字组合" autocomplete="off" class="layui-input" />
                        </div>
                    </div>
                </div>

                <div class="layui-row layui-col-space10 layui-form-item">
                    <div class="layui-col-sm12">
                        <label class="layui-form-label">确认密码：</label>
                        <div class="layui-input-block">
                            <input type="password" id="confirm_password" name="confirm_password" value="" placeholder="建议字母和数字组合" autocomplete="off" class="layui-input" />
                        </div>
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-col-sm-offset1">
                        <p class="layui-btn layui-btn-sm layui-btn-normal" lay-submit lay-filter="LAY-info-save-submit">提交保存</p>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
		let no_img_url = '{{asset('/static/images/no_img.png')}}';
		layui.config({
			base: BASE_URL
		}).extend({
			index: 'lib/index'
		}).use(['index', 'form', 'layer'], function(){
			var form = layui.form
				,layer = layui.layer
				,$ = layui.$;

			//提交
			form.on('submit(LAY-info-save-submit)', function(obj) {
				let old_password = $("#old_password").val();
				let new_password = $("#new_password").val();
				let comfirm_password = $("#comfirm_password").val();
				let email_text = $("#email").val();

				let p_test = /^[A-Za-z0-9]{6,20}$/;
				let e_test = /^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/;

				//校验密码 是否相等 是否符合要求
				if(old_password != '') {
					if(new_password == '') {
						$("#new_password").focus();
						layer.msg('新密码不能为空', {icon: 5});
						return false;
					}

					if(!p_test.test(new_password)) {
						$("#new_password").focus();
						layer.msg('密码格式不正确', {icon: 5});
						return false;
					}

					if(new_password != comfirm_password) {
						$("#comfirm_password").focus();
						layer.msg('确认密码不正确', {icon: 5});
						return false;
					}
				}

				//校验邮箱
				if(email_text != '') {
					if(!e_test.test(email_text)) {
						layer.msg('邮箱格式不正确', {icon: 5});
						return false;
					}
				}

				$.ajax({
					url: '{{route('admin.system.saveinfo')}}',
					type: 'post',
					data: obj.field,
					headers:{
						'X-CSRF-TOKEN' : '{{csrf_token()}}'
					},
					dataType: 'json',
					success:function(json){
						if(json.code == 200) {
							layer.msg(json.msg, { icon: 1});
						}
						else {
							layer.msg(json.msg, { icon: 5});
						}

						return false;
					},
					error:function(json){
						layer.msg('服务器请求失败，请刷新重试！', { icon: 5});
						return false;
					}
				});
			});
		});
    </script>
@endsection