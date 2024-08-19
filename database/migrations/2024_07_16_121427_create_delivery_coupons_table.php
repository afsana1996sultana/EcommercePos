<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_code', 50);
            $table->float('limit_per_user', 20,2)->default(0.00);
            $table->float('total_use_limit', 20,2)->default(0.00);
            $table->date('expire_date', 20)->nullable();
            $table->unsignedTinyInteger('type')->default('1')->comment('1=>All Customers, 0=>Specific Customer');
            $table->unsignedTinyInteger('producttype')->default('1')->comment('1=>All products, 0=>Specific product');
            $table->string('user_id')->nullable();
            $table->string('product_id')->nullable();
            $table->longtext('description')->nullable();
            $table->unsignedTinyInteger('status')->default(1)->comment('1=>Active, 0=>Inactive');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('delivery_coupons');
    }
}
