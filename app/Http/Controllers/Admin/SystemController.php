<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseAdminController;
use App\Libs\Helpers;
use Illuminate\Support\Facades\DB;

class SystemController extends BaseAdminController
{
	/**
	 * 修改当前登录用户信息
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function changeinfo()
	{
		//获取基本信息 可修改密码 不可以换组、编辑超管，自身状态
		$adminId = session()->get('admin_info')['id'];
		$adminInfo = DB::table('admins')->where('id', $adminId)->first();
		return view('admin.system.changeinfo', compact('adminInfo'));
	}

	/**
	 * 保存用户信息
	 *
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	public function saveinfo()
	{
		//更新到数据库 更新到session中
		$adminInfo = session()->get('admin_info');
		$postData = request()->all();
		$updateData = [];
		if (!empty($postData['username'])) {
			$updateData['name'] = Helpers::filterWords($postData['username']);
		}

		if (!empty($postData['head_url'])) {
			$updateData['head_url'] = Helpers::filterWords($postData['head_url']);
		}

		if (!empty($postData['email'])) {
			$updateData['email'] = Helpers::filterWords($postData['email']);
		}

		if (!empty($postData['desc'])) {
			$updateData['desc'] = Helpers::filterWords($postData['desc']);
		}

		//判断是否有密码提交 有则校验是否准确
		if(!empty($postData['old_password'])){
			$oldpassword = bcrypt($postData['old_password']);
			if($adminInfo['password'] != $oldpassword) {
				return $this->returnJson(101, '修改失败，当前密码错误！');
			}

			$updateData['password'] = bcrypt(Helpers::filterWords($postData['new_password']));
		}

		$result = DB::table('admins')->where('id', $adminInfo['id'])->update($updateData);
		if($result === false) {
			return $this->returnJson(102, '修改失败');
		}

		return $this->returnJson(200, '修改成功，建议退出重新登录！');
	}


	/**
	 * 系统日志列表
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function logs()
	{
		return view('admin.system.logs');
	}

	/**
	 * 获取系统日志列表
	 *
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	public function logslist()
	{
		$pageData = request()->only(['page', 'limit']);
		$page = intval($pageData['page']) > 0 ? $pageData['page'] : 1;
		$limit = intval($pageData['limit']) > 0 ? $pageData['limit'] : 10;

		$getWhere = [['is_del', '=', 1]];
		$adminInfo = session()->get('admin_info');
		if($adminInfo['is_system'] != 1)
		{
			array_push($getWhere, ['admin_id', '=', $adminInfo['id']]);
		}

		$adminLogsList = DB::table('admin_logs')->where($getWhere)->orderByDesc('ctime')->paginate($limit, ['*'], 'page', $page);
		if(!empty($adminLogsList))
		{
			foreach ($adminLogsList as &$alist) {
				$alist->ctime_text = date('Y-m-d H:i:s', $alist->ctime);
			}

			unset($alist);
		}

		return $this->returnJson(0, '获取系统日志列表成功', ['list' => $adminLogsList->all(), 'count' => $adminLogsList->total()], 'table');
	}
}
