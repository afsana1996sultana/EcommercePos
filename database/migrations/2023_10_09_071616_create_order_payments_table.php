<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Order::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnDelete();
            $table->string('invoice_no');
            $table->float('amount', 8,2)->default(0.00);
            $table->float('paid', 8,2)->default(0.00);
            $table->float('due', 8,2)->default(0.00);
            $table->float('discount', 8,2)->default(0.00);
            $table->string('payment_date')->nullable();
            $table->string('payment_method', 100);
            $table->string('transaction_num');
            $table->string('agent_number');
            $table->unsignedTinyInteger('type')->default(1)->comment('0=>Return, 1=>Payment');
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
        Schema::dropIfExists('order_payments');
    }
}