<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class AuthAdmin extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guard)
    {
    	if ($guard[0] == "api") {
		    $credentials = $request->only('username', 'password');
		    if (empty($credentials['username']) || empty($credentials['password'])) {
			    return response()->json(['code' => 101, 'msg' => '参数有误']);
		    }

		    $result = Auth::guard("admin")->attempt($credentials, false);
		    if (!$result) {
			    return response()->json(['code' => 102, 'msg' => '登录失败']);
		    }

		    //Auth::guard("admin")->user()->id; //获取用户信息
		    //md5串写入redis缓存 判断缓存存在验证通过 并返回md5串 不存在登录验证 验证通过返回缓存md5串 缓存用户的信息及可以操作的菜单列表 树状
	    }

	    if ($guard[0] == "admin") {
			if (empty(session()->get('pc_admin_info'))) {
				return response()->redirectTo(route("admin.login"));
			}
	    }

        return $next($request);
    }
}
