<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseAdminController;
use App\Libs\Helpers;
use Illuminate\Support\Facades\DB;

class MenuController extends BaseAdminController
{
	/**
	 * 菜单管理首页
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		//获取全部的菜单
		$menuList = DB::table('menu')->where([
			['is_del', '=', 1]
		])->orderBy('sort', 'desc')->get();

		$tree_menu = Helpers::tree_menu($menuList, 0);
		return view('admin.menu.index', ['tree_menu' => $tree_menu]);
	}

	/**
	 * 编辑/新增菜单
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function editmenu()
	{
		$menuId = request()->get('mid', '');
		$menuInfo = [];
		if(!empty($menuId))
		{
			//获取菜单信息
			$menuInfo = DB::table('menu')->where([
				['is_del', '=', 1],
				['id', '=', $menuId]
			])->first();
		}

		//获取菜单列表
		$menuList = DB::table('menu')->where([
			['is_del', '=', 1]
		])->orderBy('sort', 'desc')->get();
		$tree_menu = Helpers::tree_menu($menuList, 0);

		return view('admin.menu.editmenu', ['menuInfo' => $menuInfo, 'tree_menu' => $tree_menu]);
	}

	/**
	 * 保存菜单
	 *
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	public function savemenu()
	{
		$postData = request()->all(); //获取所有传递得参数
		$insertData = [];

		$parent_id = intval($postData['parent_id']);
		if(!empty($parent_id))
		{
			$insertData['parent_id'] = $parent_id;
		} else {
			$insertData['parent_id'] = 0;
		}

		$name = trim($postData['title']);
		if(!empty($name))
		{
			$insertData['name'] = $name;
		}

		$desc = trim($postData['desc']);
		if(!empty($name))
		{
			$insertData['desc'] = $desc;
		}

		$is_display = intval($postData['is_display']);
		if(!empty($is_display))
		{
			$insertData['is_display'] = $is_display;
		}

		$url_as = trim($postData['url_as']);
		if(!empty($name))
		{
			$insertData['url_as'] = $url_as;
		}

		$icon_text = trim($postData['icon_text']);
		if(!empty($icon_text))
		{
			$insertData['icon_text'] = $icon_text;
		}

		$sort = intval($postData['sort']);
		if(!empty($sort))
		{
			$insertData['sort'] = $sort;
		}

		$status = intval($postData['status']);
		if(!empty($status))
		{
			$insertData['status'] = $status;
		}

		if(!empty($postData['mid']))
		{
			//修改菜单
			$result = DB::table('menu')->where('id', $postData['mid'])->update($insertData);
		}
		else
		{
			$insertData['ctime'] = time();
			$insertData['is_del'] = 1;

			//新增菜单
			$result = DB::table('menu')->insert($insertData);
		}

		if($result === false)
		{
			return $this->returnJson(101, '操作失败！');
		}

		return $this->returnJson(200, '操作成功！');
	}

	/**
	 * 删除菜单
	 *
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	public function deletemenu()
	{
		$menuId = intval(request()->get('mid'));
		if(empty($menuId))
		{
			$msg = '页面不存在，请刷新本窗口重试！';
			return $this->returnJson(101, $msg);
		}

		//检查是否还有子类
		$isExistWhere = [
			['parent_id', '=', $menuId],
			['is_del', '=', 1]
		];

		$isExistSonCate = DB::table('menu')->where($isExistWhere)->count();
		unset($isExistWhere);
		if($isExistSonCate > 0)
		{
			return $this->returnJson(102, '删除失败,该菜单还有子类菜单！');
		}
		DB::beginTransaction();
		$deleteWhere = [
			['is_del', '=', 1],
			['id', '=', $menuId]
		];

		//删除菜单
		$result = DB::table('menu')->where($deleteWhere)->update(['is_del' => 2]);
		if($result === false)
		{
			DB::rollBack();
			return $this->returnJson(103, '删除失败！');
		}

		$deleteAdminMenu = [
			['is_del', '=', 1],
			['menu_id', '=', $menuId]
		];
		$deleteResult = DB::table('admin_menu')->where($deleteAdminMenu)->update(['is_del' => 2]);
		if($deleteResult === false)
		{
			DB::rollBack();
			return $this->returnJson(104, '删除失败！');
		}

		DB::commit();
		return $this->returnJson(200, '删除成功！');
	}
}
