@extends('layouts.common')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15" style="background: #fafafa;height:auto;">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <p class="pull-left layui-col-sm10">@if(!empty($adminInfo)) 编辑@else 新增@endif管理员</p>
                    </div>
                    <div class="layui-card-body">
                        <form class="layui-form">
                            @if(!empty($adminInfo))
                                <input type="hidden" value="{{$adminInfo->id}}" id="adminId" name="adminId" />
                            @else
                                <input type="hidden" value="" id="adminId" name="adminId" />
                            @endif
                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-sm10">
                                    <label class="layui-form-label">成员姓名：</label>
                                    <div class="layui-input-block">
                                        <input type="text" id="title" name="title" value="{{!empty($adminInfo->name) ? $adminInfo->name : ''}}" lay-verify="required" placeholder="" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                            </div>

                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-sm10">
                                    <label class="layui-form-label">角色组：</label>
                                    <div class="layui-input-block">
                                        <select name="groupId" lay-verify="required" @if(!empty($adminInfo) && $adminInfo->id == 1) disabled @endif >
                                            <option value="">选择角色</option>
                                            @if(!empty($groupList) && count($groupList) > 0)
                                                @foreach($groupList as $cv)
                                                    <option @if(!empty($adminInfo) && $cv->id == $adminInfo->group_id) selected @endif value="{{$cv->id}}">{{$cv->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="layui-form-item layui-col-sm10">
                                <label class="layui-form-label">成员描述：</label>
                                <div class="layui-input-block">
                                    <textarea name="desc" id="desc" placeholder="" class="layui-textarea">{{!empty($adminInfo->desc) ? $adminInfo->desc : ''}}</textarea>
                                </div>
                            </div>

                            <div class="layui-form-item layui-col-sm10">
                                <label class="layui-form-label">登录账号：</label>
                                <div class="layui-input-block">
                                    <input type="text" id="username" name="username" value="{{!empty($adminInfo->username) ? $adminInfo->username : ''}}" lay-verify="required" placeholder="默认为全英文名称，建议英文+数字，比如：name001" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item layui-col-sm10">
                                <label class="layui-form-label">登录密码：</label>
                                <div class="layui-input-block">
                                    <input type="password" id="password" name="password" value="" @if(empty($adminInfo)) lay-verify="required" @endif  placeholder="登录密码，编辑提交即是修改密码" autocomplete="off" class="layui-input">
                                </div>
                                <p class="text-warning" style="color:red;">密码长度6~20位，包含字母和数字</p>
                            </div>

                            <div class="layui-form-item layui-col-sm10">
                                <label class="layui-form-label">确认密码：</label>
                                <div class="layui-input-block">
                                    <input type="password" id="cpassword" name="cpassword" value="" @if(empty($adminInfo)) lay-verify="required" @endif placeholder="确认密码，编辑提交即是修改密码" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item layui-col-sm10">
                                <label class="layui-form-label">头像：</label>
                                <div class="layui-input-block">
                                    <input type="text" readonly id="head_url" name="head_url" value="{{!empty($adminInfo->head_url) ? $adminInfo->head_url : ''}}" placeholder="用户自行设置" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item layui-col-sm10">
                                <label class="layui-form-label">邮箱：</label>
                                <div class="layui-input-block">
                                    <input type="text" id="email" name="email" value="{{!empty($adminInfo->email) ? $adminInfo->email : ''}}" placeholder="" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item layui-col-sm10">
                                <label class="layui-form-label">手机号码：</label>
                                <div class="layui-input-block">
                                    <input type="text" id="mobile" name="mobile" value="{{!empty($adminInfo->phone) ? $adminInfo->phone : ''}}"  placeholder="" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item layui-col-sm10">
                                <label class="layui-form-label">是否超管：</label>
                                <div class="layui-input-block">
                                    <input type="radio" @if(!empty($adminInfo) && $adminInfo->is_system == 1 || empty($adminInfo)) checked @endif name="is_system" value="1" title="启用" />
                                    <input type="radio" @if(!empty($adminInfo) && $adminInfo->is_system == 2) checked @endif name="is_system" value="2" title="不启用" />
                                </div>
                            </div>

                            <div class="layui-form-item layui-col-sm10">
                                <label class="layui-form-label">账户状态：</label>
                                <div class="layui-input-block">
                                    <input type="radio" @if(!empty($adminInfo) && $adminInfo->status == 1 || empty($adminInfo)) checked @endif name="status" value="1" title="正常" />
                                    <input type="radio" @if(!empty($adminInfo) && $adminInfo->status == 2) checked @endif name="status" value="2" title="停用" />
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <p class="layui-btn layui-btn-sm layui-btn-normal" lay-submit lay-filter="admin-edit-submit">提交保存</p>
                                <a href="javascript:void(0);" onclick="history.back(-1);"> <p class="pull-right layui-btn layui-btn-sm layui-btn-primary">返回列表</p></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
		layui.config({
			base: BASE_URL //静态资源所在路径
		}).extend({
			index: 'lib/index' //主入口模块
		}).use(['index', 'form'], function(){
			var $ = layui.$,form = layui.form;

			//提交
			form.on('submit(admin-edit-submit)', function(obj) {

				let password = $("#password").val();
				let cpassword = $("#cpassword").val();
				let email_text = $("#email").val();
				let phone_text = $('#mobile').val();
				let username = $("#username").val();

				let u_test = /^[A-Za-z0-9]{5,20}$/;
				let p_test = /^[A-Za-z0-9]{6,20}$/;
				let e_test = /^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/;
				let m_test = /^1(3[0-9]|4[57]|5[0-3]|5[5-9]|8[01256789])\d{8}$/i;

				if(!u_test.test(username))
				{
					layer.msg('登录账号格式不正确', {icon: 5});
					return false;
				}

				//校验密码 是否相等 是否符合要求
				if(password != '')
				{
					if(!p_test.test(password))
					{
						layer.msg('密码格式不正确', {icon: 5});
						return false;
					}

					if(password != cpassword)
					{
						layer.msg('确认密码不正确', {icon: 5});
						return false;
					}
				}

				//校验邮箱
				if(email_text != '')
				{
					if(!e_test.test(email_text))
					{
						layer.msg('邮箱格式不正确', {icon: 5});
						return false;
					}
				}

				//校验手机号
				if(phone_text != '')
				{
					if(!m_test.test(phone_text))
					{
						layer.msg('手机号码格式不正确', {icon: 5});
						return false;
					}
				}

				layer.load(1);
				$.ajax({
					url: "{{route('admin.role.saveuser')}}"
					,data: obj.field
					,type:'post'
					,dataType:'json'
					,headers:{
						'X-CSRF-TOKEN': '{{csrf_token()}}'
					}
					,success: function(res) {
						layer.closeAll();
						if(res.code == 200) {
							layer.msg(res.msg, {icon: 1});
							setInterval(function(){
								window.location.href = "{{route('admin.role.users')}}";
							}, 1000);
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
			});
		});
    </script>
@endsection