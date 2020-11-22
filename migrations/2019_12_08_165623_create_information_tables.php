<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInformationTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // 好友策略
        Schema::create('information_friend_policies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index()->comment('会员ID');
            $table->string('question', 66)->nullable()->comment('问题');
            $table->string('answer')->nullable()->comment('答案');
            $table->string('password')->nullable()->comment('密码');
            $table->timestamps();
        });

        // 好友关系
        Schema::create('information_friends', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index()->comment('会员ID');
            $table->unsignedBigInteger('friend_id')->index()->comment('好友ID');
            $table->string('friend_name', 66)->nullable()->comment('好友备注名称');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态');
            $table->timestamps();
        });

        // 好友消息
        Schema::create('information_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('information_friend_id')->index()->comment('好友关系');
            $table->unsignedBigInteger('user_id')->index()->comment('发送会员');
            $table->unsignedTinyInteger('type')->default(0)->comment('信息类型');
            $table->text('message')->comment('信息内容');
            $table->unsignedTinyInteger('status')->default(0)->comment('接收状态');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('information_friend_policies');
        Schema::dropIfExists('information_friends');
        Schema::dropIfExists('information_messages');
    }
}
