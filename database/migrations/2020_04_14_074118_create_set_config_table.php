<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('set_config', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('自增ID');
            $table->string('config_key', 100)->comment('键');
            $table->string('config_value', 255)->comment('键值');
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
        Schema::dropIfExists('set_config');
    }
}
