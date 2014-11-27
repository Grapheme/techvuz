<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderPayments extends Migration {

    public function up(){
        if (!Schema::hasTable('order_payments')) {
            Schema::create('order_payments', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('order_id')->default(0)->nullable()->unsigned()->index();
                $table->float('price')->default(0)->unsigned()->nullable();
                $table->string('payment_number',50)->nullable();
                $table->timestamp('payment_date');
                $table->timestamps();
            });

            echo(' + ' . 'order_payments' . PHP_EOL);
        } else {
            echo('...' . 'order_payments' . PHP_EOL);
        }
    }

    public function down(){
        Schema::drop('order_payments');
        echo(' - ' . 'order_payments' . PHP_EOL);
    }

}
