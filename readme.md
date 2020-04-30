## 关于 Laravel6.0-tool 脚手架工具

1、其中使用了layerui 为后端视图，仅用于学习使用，不用于商业用途 如有侵权，请联系qq:741623760删除

2、后端php框架为laravel6.0来源于laravel php社区网络下载

3、本工具为自己学习搭建的一个框架结构，不做与商业用途。

### 使用

1、在.env文件中配置好对应的数据库，有对应的sql文件

2、composer install 安装完成之后

3、执行 php artisan migrate 命令安装数据库

4、配置自己所在的域名 并修改 config/app.php文件中的域名配置

5、其他与laravel开发方式一样

6、后台地址查看route/admin.php路由文件，帐号密码 admin admin888

### 关于其他

1、中间件中含有api和admin的判断，api分版本使用，config/service_url.php 为第三方服务url配置

2、已经附带RBAC权限管理，其他功能暂未添加

3、php7.2版本要求，有开启opcache扩展
