<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use Illuminate\Http\Request;

class BaseApiController extends Controller
{
	public function __construct()
	{
		//记录每一次访问
		if(config('app.env') == 'dev' || config('app.env') == 'test') {
			$now_time = time();
			$url = request()->route()->getAction();
			$content = 'request_time:' . date('Y-m-d H:i:s', $now_time)
				.' | request_url_name:'.$url['as']
				.' | request_data:'.json_encode(request()->all());

			Helpers::writeLog('laravel-api-'.date('Y-m-d', $now_time), $content);
		}
	}

	/**
	 * 获取平台基本信息 并写入缓存
	 * @return array
	 */
	public function initConfig() :array
	{
		//从缓存中获取，如果没找到，初始化平台配置信息，返回结果
		$config = [];
		return $config;
	}

	/**
	 * json返回公共方法
	 *
	 * @var $code = '' 100-199 错误信息 200 请求成功
	 * @var $msg = ''
	 * @data $data = []
	 * @type $type = 'json'
	 *
	 */
	protected function returnJson($code = 200, $msg = '', $data = array(), $listtype = 'list', $datatype = 'json')
	{
		$nowEvn = config('app.env');
		$msg = !empty($msg) ? $msg : '响应失败，请刷新页面重试！';
		$return_arr = ['code' => $code, 'msg' => $msg];

		if($listtype == 'table') {
			$return_arr['data'] = $data['list'];
			$return_arr['count'] = $data['count'];
		}
		else {
			if(!empty($data) && count($data) > 0) {
				$return_arr['data'] = $data;
			}
			else {
				$return_arr['data'] = [];
			}
		}

		$now_time = time();
		if($nowEvn == 'dev' || $nowEvn == 'test') {
			$return_arr['request_time'] = date('Y-m-d H:i:s', $now_time);
			$return_arr['request_ctime'] = $now_time;
		}

		switch ($datatype) {
			case 'json':$return = json_encode($return_arr);break;
			case 'array':$return = $return_arr;break;
			case 'html':;break;
			default:$return = json_encode($return_arr);
		}

		if($nowEvn == 'dev' || $nowEvn == 'test') {
			$request_url = request()->route()->getAction();
			$content = 'response_time:' . date('Y-m-d H:i:s', $now_time)
				. ' | request_url_name:' . $request_url['as']
				. ' | response_data:' . $return;

			Helpers::writeLog('laravel-api-'.date('Y-m-d', $now_time), $content);
		}

		return response($return);
	}
}
