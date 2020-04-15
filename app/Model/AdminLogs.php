<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AdminLogs extends Model
{
	protected $table = 'admin_logs';

	public function record($data)
	{
		//sprintf('管理员<span">%s</span>于<span>%s</span><span>%s</span><span>%s</span>')
		//姓名  时间  操作(登录 新增/删除/导出/修改/审核) 主题
		$content = sprintf('管理员<span>%s</span>于<span>%s</span><span>%s</span><span>%s</span>',
			$data[0], date("Y-m-d H:i:s", $data[1]), $data[2], $data[3]);

		$insertData = [
			'admin_id' => Auth::guard("admin")->user()->id,
			'admin_name' => Auth::guard("admin")->user()->username,
			'content' => $content,
			'from_ip' => request()->getClientIp(),
			'ctime' => time()
		];
		return $this->insert($insertData);
	}
}
