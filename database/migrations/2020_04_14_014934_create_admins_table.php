<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
	        $table->bigIncrements('id')->comment('自增ID');
	        $table->bigInteger('role_id', false, false)->comment("角色Id");
	        $table->string('username', 50)->comment('用户名称,默认为对应用户中文拼音');
	        $table->string('password', 255)->comment('用户登录密码');
	        $table->string('name', 50)->comment('实名');
	        $table->string('desc', 100)->comment('描述，50个字');
	        $table->string('head_url', 255)->nullable()->comment('用户头像');
	        $table->string('email', 100)->default('')->nullable()->comment('邮箱');
	        $table->string('phone', 20)->default('')->nullable()->comment('手机号码');
	        $table->tinyInteger('is_system', false)->default(1)->comment('1为有权限 2为超级管理员');
	        $table->tinyInteger('status', false)->default(1)->comment('1正常 2关闭');
	        $table->string('ctime', 30)->comment('创建时间戳');
	        $table->tinyInteger('is_del', false)->default(1)->comment('1正常 2删除');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
