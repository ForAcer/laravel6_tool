<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_role', function (Blueprint $table) {
	        $table->bigIncrements('id');
	        $table->string('name', 50)->default('')->comment('角色名称');
	        $table->string('desc', 100)->default('')->nullable()->comment('描述');
	        $table->tinyInteger('status', false)->default(1)->comment('状态 1正常 2关闭');
	        $table->tinyInteger('is_del', false)->default(1)->comment('状态 1正常 2删除');
	        $table->string('ctime', 20)->default('')->comment('创建时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_role');
    }
}
