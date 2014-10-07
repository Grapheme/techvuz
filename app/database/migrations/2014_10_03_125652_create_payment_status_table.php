<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentStatusTable extends Migration {

	public function up(){
        if (!Schema::hasTable('payment_status')) {
            Schema::create('payment_status', function(Blueprint $table) {
                $table->increments('id');
                $table->string('title',50)->nullable();
                $table->string('class',20)->nullable();
                $table->timestamps();
            });

            echo(' + ' . 'payment_status' . PHP_EOL);
        } else {
            echo('...' . 'payment_status' . PHP_EOL);
        }
	}

	public function down(){
		Schema::drop('payment_status');
        echo(' - ' . 'payment_status' . PHP_EOL);
	}

}
