<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseAdminController;
use App\Libs\Helpers;
use Illuminate\Http\Request;

class MainController extends BaseAdminController
{
	public function index()
    {
	    $adminInfo = session()->get('pc_admin_info');

	    //判断是否是超级管理员如果是 则判定获取所有菜单，如果不是则获取可操作模块菜单 支持到三级 更多级要修改视图
	    $menuListData = Helpers::getMenuList($adminInfo, 'main');
	    $menu_list = Helpers::tree_menu($menuListData, 0);
	    return view('admin.main.index', [ 'adminInfo' => $adminInfo, 'menuList' => $menu_list ]);
    }

    public function home()
    {

    }
}
