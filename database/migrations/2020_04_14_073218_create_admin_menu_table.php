<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_menu', function (Blueprint $table) {
	        $table->bigIncrements('id');
	        $table->smallInteger('role_id', false)->comment('角色ID');
	        $table->smallInteger('menu_id', false)->comment('菜单ID');
	        $table->string('ctime', 20)->default('')->comment('创建时间');
	        $table->tinyInteger('is_del', false)->comment('1正常 2删除');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_menu');
    }
}
