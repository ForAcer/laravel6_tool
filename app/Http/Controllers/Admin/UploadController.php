<?php

/**
 * 上传文件控制器类
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseAdminController;
use App\Libs\Helpers;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class UploadController extends BaseAdminController
{
	/**
	 * 上传文件公共方法
	 *
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 * @throws \Exception
	 */
	public function uploadOne()
	{
		$fileObj = request()->file('file');
		if(empty($fileObj)) {
			return $this->returnJson(101, '请选择文件之后再上传文件！');
		}

		$fileSize = $fileObj->getSize();
		if(intval($fileSize/1024) > config('upload.file_size_limit')) {
			return $this->returnJson(102, '文件大小超出限制50M！');
		}

		$fileExt = $fileObj->getClientOriginalExtension();
		$fileName = $fileObj->getClientOriginalName();
		$fileCtype = $fileObj->getClientMimeType();

		$fileNameArr = explode('.', $fileName);
		$insertData = [];
		$nowTime = time();

		$insertData['title'] = $fileNameArr[0];
		$insertData['file_md5'] = md5($fileName);
		$insertData['file_suffix'] = $fileExt;
		$nowDir = date('Ymd', $nowTime);

		$dir_url = './data/upload_file/'.$nowDir;
		$file_url = '/data/upload_file/'.$nowDir;
		$dir_small_url = '/data/small/'.$nowDir;
		//$dir_webp_url = '/data/webp/'.$nowDir;
		$dir_thumb_url = '/data/thumb/'.$nowDir;

		//检查当前日期上传文件夹是否存在 不存在则生成
		if(!is_dir($dir_url))
		{
			mkdir($dir_url, 0775, true);
			mkdir('.'.$dir_small_url, 0775, true);
			//mkdir('.'.$dir_webp_url, 0777, true);
			mkdir('.'.$dir_thumb_url, 0775, true);
		}

		//把文件重命名存入到文件夹中 做处理
		$file_name =  Helpers::resetFileName($fileNameArr[0], $nowTime);
		$file_path = $fileObj->move($dir_url, $file_name.'.'.$fileExt);
		if(in_array($fileExt, config('upload.image_type'))) {
			$imageObj = Image::make($file_path);
			$insertData['file_type'] = 1;
			$insertData['image_width'] = $imageObj->width();
			$insertData['image_height'] = $imageObj->height();

			//判断当前日期文件夹是否存在 不存在则生成 thumb file_webp file_small 都要检查
			$thumb_file_name = '.'.$dir_thumb_url.'/thumb_'.$file_name.'.'.$fileExt;
			$imageObj->resize(80, 80)->save($thumb_file_name);
			if(is_file($thumb_file_name))
			{
				$insertData['thumb_url'] = $dir_thumb_url.'/thumb_'.$file_name.'.'.$fileExt;  //80*80缩放图片
			}
			else
			{
				throw new \Exception('生成thumb图片失败');
			}

			unset($imageObj);
			$imageObj = Image::make($file_path);
			$small_file_name = '.'.$dir_small_url.'/small_'.$file_name.'.'.$fileExt;
			$imageObj->save($small_file_name, 60);
			if(is_file($small_file_name))
			{
				$insertData['file_small'] = $dir_small_url.'/small_'.$file_name.'.'.$fileExt; //低质量图片
			}
			else
			{
				throw new \Exception('生成small图片失败');
			}

			$insertData['file_webp'] = '';

			//生成webp格式图片
//			$imagick = new \Imagick();
//			$imagick->readImage($file_path);
//			$webp_file_name = '.'.$dir_webp_url.'/webp_'.$file_name.'.webp';
//			$imagick->writeImage($webp_file_name);
//			if(is_file($webp_file_name))
//			{
//				$insertData['file_webp']= $dir_webp_url.'/webp_'.$file_name.'.webp'; // 高质量图片，低容量
//			}
//			else
//			{
//				throw new \Exception('生成webp图片失败');
//			}
		}
		else {
			$insertData['file_type'] = 2;
			$insertData['image_width'] = 0;
			$insertData['image_height'] = 0;
		}

		//获取文件实例如果是图片则走图片流程 不是走其他文件流程
		$adminInfo = session()->get('admin_info');
		$insertData['user_id'] = -1;
		$insertData['admin_id'] = $adminInfo['id'];
		$insertData['file_url'] = $file_url .'/'. $file_name .'.'. $fileExt; //源文件路径
		$insertData['file_size'] = $fileSize;
		$insertData['file_ctype'] = $fileCtype;
		$insertData['ctime'] = $nowTime;

		//保存到数据库中
		$result = DB::table('resource')->insertGetId($insertData);
		if($result === false)
		{
			unlink($file_path);
			if($insertData['file_type'] == 1)
			{
				unlink('.'.$dir_small_url.'/small_'.$file_name.'.'.$fileExt);
				unlink('.'.$dir_thumb_url.'/thumb_'.$file_name.'.'.$fileExt);
				//unlink('.'.$dir_webp_url.'/webp_'.$file_name.'.'.$fileExt);
			}

			return $this->returnJson(103, '上传失败');
		}

		return $this->returnJson(200, '上传成功', ['res_id' => $result, 'file_samll_url' => !empty($insertData['thumb_url']) ? $insertData['thumb_url']: '']);
	}

	public function uploadMore()
	{

	}

	public function uploadToOss()
	{

	}

	public function uploadToRemote()
	{

	}
}
