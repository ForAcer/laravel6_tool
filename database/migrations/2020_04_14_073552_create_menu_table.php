<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
	        $table->bigIncrements('id');
	        $table->bigInteger('parent_id', false)->comment('菜单父类');
	        $table->string('name', 50)->default('')->comment('菜单名称');
	        $table->string('desc', 100)->default('')->comment('菜单描述');
	        $table->tinyInteger('is_display', false)->default(1)->comment('1显示 2不显示 是否显示');
	        $table->string('url_as', 50)->default('')->comment('路由名称');
	        $table->string('icon_text', 30)->default('')->comment('菜单图标');
	        $table->smallInteger('sort', false)->default(0)->comment('菜单排序');
	        $table->tinyInteger('status', false)->default(1)->comment('1启用 2不启用 是否启用');
	        $table->string('ctime', 20)->default('')->comment('创建时间');
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
        Schema::dropIfExists('menu');
    }
}
