<?php

/**
 * @author : liuxinhui
 * @email : liuxinhui@haiwang.link
 * @time : 2020/4/15 9:36
 * @company : 深圳海王集团股份有限公司
 * @web : https://www.neptunus.com
 */

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>管理系统登录</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="{{asset('static/common/layui/css/layui.css')}}?{{$BK_VERSION}}" media="all">
    <link rel="stylesheet" href="{{asset('static/common/style/admin.css')}}?{{$BK_VERSION}}" media="all">
    <link rel="stylesheet" href="{{asset('static/common/style/login.css')}}?{{$BK_VERSION}} media="all">
    <script src="{{asset('static/common/layui/layui.js')}}?{{$BK_VERSION}}"></script>
    <script src="{{asset('static/js/common.js')}}?{{$BK_VERSION}}"></script>
    <script>
		const SITE_URL = '{{$SITE_URL}}';
		const hwObj = new HW();
		const BASE_URL = SITE_URL + '/static/common/';
    </script>
    <script>
		/**
		 * @author : liuxinhui
		 * @email : liuxinhui@haiwang.link
		 * @company : 深圳海王集团股份有限公司
		 * @web : https://www.neptunus.com
		 */
    </script>
</head>
<body>
@yield('content')
</body>
</html>

