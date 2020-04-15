<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>管理系统</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="{{asset('static/common/layui/css/layui.css')}}?{{$BK_VERSION}}" media="all">
    <link rel="stylesheet" href="{{asset('static/common/style/admin.css')}}?{{$BK_VERSION}}" media="all">
    <script src="{{asset('static/common/layui/layui.js')}}?{{$BK_VERSION}}"></script>
    <script src="{{asset('static/js/common.js')}}?{{$BK_VERSION}}"></script>
    <script>
		const SITE_URL = '{{$SITE_URL}}';
		const hwObj = new HW();
		const BASE_URL = SITE_URL + '/static/common/';
    </script>
</head>
<body>
@yield('content')
</body>
</html>
