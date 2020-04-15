<?php
namespace App\Libs;

use Illuminate\Support\Facades\DB;

class Helpers{

	/**
	 * 获取后台首页菜单列表
	 *
	 * 判断是否是超级管理员是则获取所有菜单，不是则获取对应可操作的菜单
	 *
	 * @param array $menu_list
	 */
	public static function getMenuList($adminInfo = [], $from_type = '')
	{
		//是超级管理员
		if($adminInfo['is_system'] === 1) {
			$where = [
				['status', '=', 1],
				['is_del', '=', 1]
			];

			if($from_type == 'main') {
				array_push($where, ['is_display', '=', 1]);
			}

			$menuList = DB::table('menu')->where($where)->orderBy('sort', 'desc')->get();
		} else {
			$where = [
				['admin_menu.role_id', '=', $adminInfo['role_id']],
				['admin_menu.is_del', '=', 1]
			];

			if($from_type == 'main') {
				array_push($where, ['menu.is_display', '=', 1]);
			}

			//用group_id查询可操作节点id,再用操作节点获取可以操作的菜单
			$menuList = DB::table('admin_menu')->where($where)
				->join('menu', 'admin_menu.menu_id', '=', 'menu.id')
				->orderBy('menu.sort', 'desc')->get('menu.*');
		}

		return $menuList;
	}

	/**
	 * 记录自定义日志
	 * @param $file 日志文件名
	 * @param $content 日志内容
	 */
	public static function writeLog($file_name, $content = '')
	{
		//记录日志
		$content = date('Y-m-d H:i:s') . ' ' .$content . PHP_EOL;
		$file = 'logs/' . date('Y-m-d') . '/' . $file_name .'.log';
		$filename = storage_path($file);
		$dir = dirname($filename);
		if(!file_exists($dir)) {
			mkdir($dir, 0755);
		}

		return file_put_contents($filename, $content, FILE_APPEND);
	}

	/**
	 * 重命名文件名称，并防止重复。
	 *
	 * @param $name
	 * @param string $time
	 * @return string
	 */
	public static function resetFileName($name, $time = '')
	{
		$rand_num = rand(11111111, 99999999);
		$file_name_str = md5(md5($name.$time).$rand_num);
		return substr($file_name_str, 0, 4).substr($file_name_str, 12, 4).substr($file_name_str, 20, 4).substr($file_name_str, 28, 4);
	}

	/**
	 * 过滤参数
	 * @param string $str 接受的参数
	 * @return string
	 */
	public static function filterWords($str)
	{
		$farr = array(
			"/<(\\/?)(script|i?frame|style|html|body|title|link|meta|object|\\?|\\%)([^>]*?)>/isU",
			"/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",
			"/select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile|dump/is"
		);
		$str = preg_replace($farr,'',$str);
		return $str;
	}

	/**
	 * 获取数组二维数组中某一列的所有值
	 *
	 * @param array $arr_data
	 * @return mixed $data
	 */
	public static function getSubValueArr($arr_data = [], $arr_key = '') :array
	{
		$data = [];
		foreach ($arr_data as $value)
		{
			if(!empty($value[$arr_key]))
			{
				array_push($data, $value[$arr_key]);
			}
		}

		$result = array_unique($data);
		return $result;
	}

	/**
	 * 获取对象二维数组中某一列的所有值
	 *
	 * @param array $arr_data
	 * @return mixed $data
	 */
	public static function getSubValueObj($arr_data = [], $arr_key = '') :array
	{
		$data = [];
		foreach ($arr_data as $value)
		{
			if(!empty($value->$arr_key))
			{
				array_push($data, $value->$arr_key);
			}
		}

		$result = array_unique($data);
		return $result;
	}

	/**
	 * 无限分类递归方法(菜单)
	 *
	 * @param array $data
	 * @param int $parent_id
	 * @return array
	 */
	public static function tree_menu($data = [], $parent_id = 0)
	{
		$newArr = [];
		foreach ($data as $key => $value)
		{
			if($value->parent_id == $parent_id)
			{
				$newArr[$value->id] = $value;
				$newArr[$value->id]->children = self::tree_menu($data, $value->id);
			}
		}

		return $newArr;
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
}