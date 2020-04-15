<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_logs', function (Blueprint $table) {
	        $table->bigIncrements('id')->comment('自增ID');
	        $table->bigInteger('admin_id', false)->comment('管理员ID');
	        $table->string('admin_name', 50)->default('')->comment('管理员名称');
	        $table->text('content')->comment('内容');
	        $table->string('from_ip', 30)->default('')->comment('来源ip');
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
        Schema::dropIfExists('admin_logs');
    }
}
