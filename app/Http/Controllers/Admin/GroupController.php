<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseAdminController;
use App\Libs\Helpers;
use Illuminate\Support\Facades\DB;

class GroupController extends BaseAdminController
{
	/**
	 * 角色管理首页
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		return view('admin.group.index');
	}

	/**
	 * 获取角色列表
	 *
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	public function grouplist()
	{
		$pageData = request()->only(['page', 'limit']);
		$page = intval($pageData['page']) > 0 ? $pageData['page'] : 1;
		$limit = intval($pageData['limit']) > 0 ? $pageData['limit'] : 10;
		$groupList = DB::table('admin_role')->where([
			['is_del', '=', 1]
		])->orderByDesc('ctime')->paginate($limit, ['*'], 'page', $page);

		if(!empty($groupList))
		{
			foreach ($groupList as &$glist) {
				$glist->status_text = $glist->status == 1 ? '启用' : '关闭';
				$glist->ctime_text = date('Y-m-d H:i:s', $glist->ctime);
			}

			unset($glist);
		}

		return $this->returnJson(0, '获取角色列表成功', ['list' => $groupList->all(), 'count' => $groupList->total()], 'table');
	}

	/**
	 * 角色编辑/新增
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function editgroup()
	{
		//获取角色信息 和 可操作菜单
		$groupId = Helpers::filterWords(request()->get('groupId', ''));
		$groupInfo = [];
		if(!empty($groupId))
		{
			$groupInfo = DB::table('admin_role')->where([
				['id', '=', $groupId],
				['is_del', '=', 1]
			])->first();
		}

		return view('admin.group.editgroup', compact('groupInfo'));
	}

	public function getMenuTreeData()
	{
		$groupId = Helpers::filterWords(request()->get('groupId', ''));
		$selectedIds = [];
		$where = [['is_del', '=', 1]];

		//获取全部的菜单
		$menuListAll = DB::table('menu')->where($where)->orderBy('sort', 'desc')->get(['id', 'parent_id', 'name']);

		//获取全部得父类
		$parentIds = Helpers::getSubValueObj($menuListAll, 'parent_id');

		//获取本角色可以操作得菜单
		if(!empty($groupId))
		{
			array_push($where, ['role_id', '=', $groupId]);
			$adminMenuList = DB::table('admin_menu')->where($where)->get();
			$adminMenuIds = Helpers::getSubValueObj($adminMenuList, 'menu_id');
			$selectedIds = array_diff($adminMenuIds, $parentIds);
		}

		//返回树形结构 并处理是否选中
		$treeData = $this->tree_menu($menuListAll, $parentId = 0);
		if(!empty($treeData) && count($treeData) > 0)
		{
			return $this->returnJson(200, 'success', ['tree_data' => $treeData, 'seletedids' => count($selectedIds) > 0 ? $selectedIds : []]);
		}
		else
		{
			return $this->returnJson(0, 'fail', []);
		}
	}

	/**
	 * 返回树形结构 并处理是否选中
	 *
	 * @param $data
	 * @param int $parentId
	 * @param $selected
	 * @return array
	 */
	public function tree_menu($data, $parentId = 0)
	{
		$new_dept_arr = array();
		foreach($data as $key => $value)
		{
			if($value->parent_id == $parentId)
			{
				$new_dept_arr[$value->id] = $value;
				$new_dept_arr[$value->id]->spread = true;
				$new_dept_arr[$value->id]->children = $this->tree_menu($data, $value->id);
				if(empty($new_dept_arr[$value->id]->children))
				{
					unset($new_dept_arr[$value->id]->children);
				}
			}
		}

		return $new_dept_arr;
	}

	/**
	 * 保存角色
	 *
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	public function savegroup()
	{
		$postData = request()->all();
		$insertData = [];
		if(!empty($postData['title']))
		{
			$insertData['name'] = trim($postData['title']);
		}

		if(!empty($postData['desc']))
		{
			$insertData['desc'] = trim($postData['desc']);
		}

		DB::beginTransaction();
		$groupId = intval($postData['groupId']);
		if(!empty($groupId))
		{
			$result = DB::table('admin_role')->where('id', '=', $postData['groupId'])->update($insertData);
		}
		else
		{
			$insertData['ctime'] = time();
			$insertData['status'] = 1;
			$result = DB::table('admin_role')->insertGetId($insertData);
			$groupId = $result;
		}

		if($result === false)
		{
			DB::rollBack();
			return $this->returnJson(101, '保存失败！');
		}

		if(!empty($postData['seletedIds']))
		{
			//处理勾选得菜单
			$selectedArr = json_decode($postData['seletedIds'], true);
			if(count($selectedArr) < 0)
			{
				DB::rollBack();
				return $this->returnJson(102, '保存失败！');
			}

			//删除之前选择得菜单
			$deleteAdminMenuResult = DB::table('admin_menu')->where('role_id', $groupId)->delete();
			if($deleteAdminMenuResult === false)
			{
				DB::rollBack();
				return $this->returnJson(103, '保存失败！');
			}

			//保存勾选得菜单
			$selectedData = $this->getSelectedMenu($selectedArr);
			$selectedIds = $this->reduceArray($selectedData);
			$adminMenuInsert = [];
			$insertTime = time();
			foreach ($selectedIds as $value)
			{
				array_push($adminMenuInsert, ['role_id' => $groupId, 'menu_id' => $value, 'ctime' => $insertTime, 'is_del' => 1]);
			}

			$insertResult = DB::table('admin_menu')->insert($adminMenuInsert);
			if($insertResult  === false)
			{
				DB::rollBack();
				return $this->returnJson(104, '保存失败！');
			}
		}

		DB::commit();
		return $this->returnJson(200, '保存成功！');
	}

	/**
	 * 平铺多维数组成一维数组
	 *
	 * @param $array
	 * @return array
	 */
	public function reduceArray($array) {
		$return = [];
		array_walk_recursive($array, function ($x) use (&$return) {
			$return[] = $x;
		});
		return $return;
	}

	/**
	 * 递归获取选中的菜单ID
	 * 如果有子类菜单 则不把父类加入到选中得菜单中
	 *
	 * @param array $data
	 * @return array
	 */
	public function getSelectedMenu($data = array())
	{
		$menuIds = [];
		foreach ($data as $value)
		{
			if(!empty($value['children']))
			{
				array_push($menuIds, $this->getSelectedMenu($value['children']));
			}

			if(!empty($value['id']))
			{
				array_push($menuIds, $value['id']);
			}
		}

		return $menuIds;
	}

	/**
	 * 删除角色
	 *
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	public function deletegroup()
	{
		$groupId = Helpers::filterWords(request()->get('groupId', ''));
		if(empty($groupId)) {
			return $this->returnJson(101, '删除失败！');
		}

		if($groupId == 1) {
			return $this->returnJson(102, '超级管理员组不能删除！');
		}

		//检查该用户组下是否还有用户
		$countUser = DB::table('admins')->where([
			['is_del', '=', 1],
			['role_id', '=', $groupId]
		])->count('id');
		if($countUser > 0) {
			return $this->returnJson(104, '该用户组下还有用户！');
		}

		$result = DB::table('admin_role')->where([['is_del', '=', 1], ['id', '=', $groupId]])->update(['is_del' => 2]);
		if($result === false) {
			return $this->returnJson(103, '删除失败！');
		}

		return $this->returnJson(200, '删除成功！');
	}

	/**
	 * 角色成员首页
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function users()
	{
		return view('admin.group.users');
	}

	/**
	 * 获取角色用户列表
	 *
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	public function userlist()
	{
		$pageData = request()->only(['page', 'limit']);
		$page = intval($pageData['page']) > 0 ? $pageData['page'] : 1;
		$limit = intval($pageData['limit']) > 0 ? $pageData['limit'] : 10;
		$adminsList = DB::table('admins')->where([
			['is_del', '=', 1]
		])->orderByDesc('ctime')->paginate($limit, ['*'], 'page', $page);

		//获取角色列表
		$adminMenuList = DB::table('admin_role')->where('is_del', '=', 1)->get();
		foreach ($adminMenuList as $amlist)
		{
			$adminMenuList[$amlist->id] = $amlist;
		}
		unset($amlist);
		if($adminsList->total() > 0)
		{
			foreach ($adminsList as &$alist) {
				$alist->group_name = $adminMenuList[$alist->role_id]->name;
				$alist->is_system_text = $alist->is_system == 1 ? '是' : '不是';
				$alist->status_text = $alist->status == 1 ? '启用' : '停用';
				$alist->ctime_text = date('Y-m-d H:i:s', $alist->ctime);
			}
			unset($alist);
			$list = $adminsList->all();
			$count = $adminsList->total();
		} else {
			$list = [];
			$count = 0;
		}

		return $this->returnJson(0, '获取角色成员列表成功', ['list' => $list, 'count' => $count], 'table');
	}

	/**
	 * 编辑角色用户
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edituser()
	{
		$adminId = Helpers::filterWords(request()->get('adminId', ''));
		$adminInfoObj = [];
		if(!empty($adminId))
		{
			$adminInfoObj = DB::table('admins')->where([
				['is_del', '=', 1],
				['id', '=', $adminId]
			])->first();
		}

		//获取角色列表
		$groupList = DB::table('admin_role')->where([
			['is_del', '=', 1],
			['status', '=', 1]
		])->orderByDesc('ctime')->get(['id','name']);

		return view('admin.group.edituser', ['adminInfo' => $adminInfoObj, 'groupList' => $groupList]);
	}

	/**
	 * 保存角色用户
	 *
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	public function saveuser()
	{
		$postData = request()->all();
		$insertData = [];

		if(!empty($postData['groupId']))
		{
			$insertData['role_id'] = Helpers::filterWords($postData['groupId']);
		}

		if(!empty($postData['wx_user_id']))
		{
			$insertData['wx_user_id'] = 'test';//Helpers::filterWords($postData['wx_user_id']);
		}

		if(!empty($postData['username']))
		{
			$insertData['username'] = Helpers::filterWords($postData['username']);
		}

		if(!empty($postData['password']))
		{
			$password = Helpers::filterWords($postData['password']);
			$insertData['password'] = bcrypt($password);
		}

		if(!empty($postData['title']))
		{
			$insertData['name'] = Helpers::filterWords($postData['title']);
		}

		if(!empty($postData['desc']))
		{
			$insertData['desc'] = Helpers::filterWords($postData['desc']);
		}

		if(!empty($postData['head_url']))
		{
			$insertData['head_url'] = Helpers::filterWords($postData['head_url']);
		}

		if(!empty($postData['mobile']))
		{
			$insertData['phone'] = Helpers::filterWords($postData['mobile']);
		}

		if(!empty($postData['is_system']))
		{
			$insertData['is_system'] = Helpers::filterWords($postData['is_system']);
		}

		if(!empty($postData['status']))
		{
			$insertData['status'] = Helpers::filterWords($postData['status']);
		}

		if(!empty($postData['email']))
		{
			$insertData['email'] = Helpers::filterWords($postData['email']);
		}

		if(!empty($postData['adminId'])) {
			//修改
			$result = DB::table('admins')->where('id', '=', $postData['adminId'])->update($insertData);
		}
		else {
			//新增
			$insertData['ctime'] = time();
			$result = DB::table('admins')->insert($insertData);
		}

		if($result === false)
		{
			return $this->returnJson(101, '保存失败！');
		}

		unset($insertData);
		return $this->returnJson(200, '保存成功！');
	}

	/**
	 * 删除角色成员
	 *
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	public function deleteuser()
	{
		//超级管理员不可以移除
		$adminId = intval(request()->get('adminId'));
		if($adminId == 1)
		{
			return $this->returnJson(101, '删除失败，超级管理员不可删除！');
		}

		$result = DB::table('admins')->where('id', '=', $adminId)->update(['is_del' => 2]);
		if($result === false || $result <= 0) {
			return $this->returnJson(102, '删除失败！');
		}

		return $this->returnJson(200, '删除成功！');
	}
}
