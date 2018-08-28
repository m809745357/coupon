<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 64)->nullable()->comment('优惠券标题');
            $table->decimal('amount', 10, 2)->default(0.00)->comment('优惠券金额');
            $table->timestamp('start_time')->comment('优惠券使用开始时间');
            $table->timestamp('end_time')->comment('优惠券使用结束时间');
            $table->tinyInteger('status')->default(1)->comment('状态：1未使用，2已使用，3已失效');

            $table->unsignedInteger('user_id')->nullable()->comment('用户编号');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
