<?php

/**
 * @author : liuxinhui
 * @email : liuxinhui@haiwang.link
 * @time : 2020/4/15 9:32
 * @company : 深圳海王集团股份有限公司
 * @web : https://www.neptunus.com
 */

?>
@extends('layouts.login_common')
@section('content')
    <div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">
        <div class="layadmin-user-login-main">
            <div class="layadmin-user-login-box layadmin-user-login-header">
                <h2 style="font-weight: 600;">管理系统</h2>
                <!--<p>layui 官方出品的单页面后台管理模板系统</p>-->
            </div>
            <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-username" for="LAY-user-login-username"></label>
                    <input type="text" name="username" id="LAY-user-login-username" lay-verify="required" placeholder="用户名" class="layui-input">
                </div>
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
                    <input type="password" name="password" id="LAY-user-login-password" lay-verify="required" placeholder="密码" class="layui-input">
                </div>
                <div class="layui-form-item">
                    <div class="layui-row">
                        <div class="layui-col-xs5">
                            <label class="layadmin-user-login-icon layui-icon layui-icon-vercode" for="LAY-user-login-vercode"></label>
                            <input type="text" name="captcha_code" id="captcha_code" lay-verify="required" placeholder="图形验证码" class="layui-input">
                        </div>
                        <div class="layui-col-xs5">
                            <div style="margin-left: 3px;">
                                <img src="{{captcha_src('flat')}}" class="layadmin-user-login-codeimg" id="LAY-user-get-vercode">
                            </div>
                        </div>
                        <div class="layui-col-xs2">
                            <div style="margin-left: 5px;margin-top:5px;">
                                <button title="刷新" class="layui-btn layui-btn-sm layui-btn-checked" id="refresh-btn"><i class="layui-icon layui-icon-refresh-1"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <button id="login-btn" class="layui-btn layui-btn-fluid" lay-submit lay-filter="LAY-user-login-submit">登 入</button>
                </div>
                <div class="layui-form-item" style="margin-bottom: 20px;">
                    <input type="checkbox" name="remember" lay-skin="primary" title="同意用户协议">
                    <a href="#" class="layadmin-user-jump-change layadmin-link" style="margin-top: 7px;">忘记密码？</a>
                </div>
            </div>
        </div>
        <div class="layui-trans layadmin-user-login-footer">
            <p>© 2020~2021<a href="{{$SITE_URL}}" target="_blank">{{$SITE_URL}}</a></p>
        </div>
    </div>
    <script>
		let csrf_token = '{{csrf_token()}}';
		layui.config({
			base: BASE_URL //静态资源所在路径
		}).extend({
			index: 'lib/index' //主入口模块
		}).use(['index', 'jquery', 'form'], function(){
			const $ = layui.jquery, form = layui.form;
			$("body").keydown(function() {
				if (event.keyCode == 13) {
					$("#login-btn").click();
				}
			});

			function refreshCaptcha()
			{
				$("#captcha_code").val('');
				let url = "{{route('admin.captcha.flat')}}";
				$.get(url,function(data,status){
					$('#LAY-user-get-vercode').attr('src', data);
				});
			}

			$("#refresh-btn").on("click", function(){
				refreshCaptcha();
			});

			form.on('submit(LAY-user-login-submit)', function (obj) {
				let request_url = '{{route('admin.dologin')}}';
				$.ajax({
					url: request_url
					,data: obj.field
					,type:'post'
					,dataType:'json'
					,headers:{
						'X-CSRF-TOKEN' : csrf_token
					}
					,success: function(res){
						if(res.code == 200) {
							layer.msg(res.msg, {icon: 1});
							setTimeout(function(){
								window.location.href = res.data.url;
							}, 1200);
						}
						else {
							//刷新验证码
							refreshCaptcha();
							layer.msg(res.msg, {icon: 5});
							if(res.code == 105) {
								setTimeout(function(){
									window.location.href = res.data.url;
								}, 1500);
                            }
						}
					}
					,error:function(){
						//刷新验证码
						refreshCaptcha();
						layer.msg('服务器请求失败，请刷新页面重试');
					}
				});
			})

		});
    </script>
@endsection

