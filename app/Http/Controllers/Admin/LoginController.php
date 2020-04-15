<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseAdminController;
use App\Libs\Helpers;
use App\Model\AdminLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends BaseAdminController
{
	public $logs = null;
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if (!empty(session()->get('pc_admin_info'))) {
			return response()->redirectTo(route('admin.main.index'));
		}

		return response()->view("admin.login.index");
	}

	public function doLogin(Request $request)
	{
		$username = Helpers::filterWords($request->get('username'));
		$password = Helpers::filterWords($request->get('password'));
		$captcha_code = Helpers::filterWords($request->get('captcha_code'));
		if (empty($username) || empty($password) || empty($captcha_code)) {
			return $this->returnJson(101, '登录失败!');
		}

		if (captcha_check($captcha_code)) {
			$result = Auth::guard("admin")->attempt(['username' => $username, 'password' => $password], false);
			if (!$result) {
				return response()->json(['code' => 103, 'msg' => '登录失败,帐号或密码错误!']);
			}

			$adminInfo = Auth::guard('admin')->user()->toArray();
			if($adminInfo['status'] == 2)
			{
				return $this->returnJson(105, '登录失败，该用户被停用，请联系管理员!', ['url' => route('admin.logout')]);
			}

			//创建session
			session()->put('pc_admin_info', $adminInfo);

			//获取可操作菜单
			$menuListData = Helpers::getMenuList($adminInfo);
			$url_as_arr = Helpers::getSubValueObj($menuListData, 'url_as');
			session()->put('admin_menu_'.$adminInfo['id'], $url_as_arr);

			$this->logs = new AdminLogs();
			$data = [$adminInfo['username'], time(), '登录', '后台管理系统'];
			$this->logs->record($data);

			return $this->returnJson(200, '登录成功!', ['url' => route('admin.main.index')]);
		} else {
			return $this->returnJson(102, '验证码错误!');
		}
	}

	public function logout()
	{
		$adminId = session()->get('pc_admin_info')['id'];
		session()->remove('admin_menu_'.$adminId);  //清理session
		session()->remove("pc_admin_info");
		Auth::guard('admin')->logout();  //清理登录信息
		return $this->returnJson(200, '退出成功', ['url' => route('admin.login')]);
	}

	public function clearCache()
	{
		if (extension_loaded("opcache")) {
			opcache_reset();
		}

		return $this->returnJson(200, '清理完成！', ['url' => '']);
	}

	public function getCaptcha()
	{
		return captcha_src('flat');
	}

	public function other()
	{
		return '';
	}
}
