<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

    public function up(){
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->default(0)->nullable()->unsigned()->index();
                $table->integer('number')->default(0)->nullable()->unsigned();
                $table->boolean('completed')->default(0)->nullable();
                $table->tinyInteger('payment_status')->default(1)->nullable()->unsigned()->index();
                $table->timestamp('payment_date');
                $table->tinyInteger('discount')->default(0)->nullable();
                $table->boolean('close_status')->default(0)->nullable();
                $table->timestamp('close_date');
                $table->boolean('archived')->default(0)->nullable();

                $table->boolean('study_status')->default(0)->nullable();
                $table->timestamp('study_date');

                $table->integer('contract_id')->default(1)->nullable()->unsigned()->index();
                $table->integer('invoice_id')->default(1)->nullable()->unsigned()->index();
                $table->integer('act_id')->default(1)->nullable()->unsigned()->index();
                $table->timestamps();
            });

            echo(' + ' . 'orders' . PHP_EOL);
        } else {
            echo('...' . 'orders' . PHP_EOL);
        }

        if (!Schema::hasTable('order_listeners')) {
            Schema::create('order_listeners', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('order_id')->default(0)->nullable()->unsigned()->index();
                $table->integer('course_id')->default(0)->nullable()->unsigned()->index();
                $table->integer('user_id')->default(0)->nullable()->unsigned()->index();
                $table->float('price')->default(0)->unsigned()->nullable();
                $table->boolean('access_status')->default(0)->nullable()->unsigned();
                $table->boolean('start_status')->default(0)->nullable()->unsigned();
                $table->timestamp('start_date');
                $table->boolean('over_status')->default(0)->nullable()->unsigned();
                $table->timestamp('over_date');
                $table->boolean('start_final_test')->default(0)->nullable()->unsigned();
                $table->timestamp('start_final_test_date');
                $table->integer('certificate_number')->default(0)->nullable()->unsigned();
                $table->timestamp('certificate_date');
                $table->timestamps();
            });

            echo(' + ' . 'order_listeners' . PHP_EOL);
        } else {
            echo('...' . 'order_listeners' . PHP_EOL);
        }

        if (!Schema::hasTable('order_listener_tests')) {
            Schema::create('order_listener_tests', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('order_listeners_id')->default(0)->nullable()->unsigned()->index();
                $table->integer('chapter_id')->default(0)->nullable()->unsigned()->index();
                $table->integer('test_id')->default(0)->nullable()->unsigned()->index();
                $table->text('data_results')->nullable();
                $table->tinyInteger('result_attempt')->default(0)->nullable()->unsigned();
                $table->integer('time_attempt')->default(0)->nullable()->unsigned();
                $table->timestamps();
            });

            echo(' + ' . 'order_listener_tests' . PHP_EOL);
        } else {
            echo('...' . 'order_listener_tests' . PHP_EOL);
        }
    }

    public function down(){
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_listeners');
        Schema::dropIfExists('order_listener_tests');
        echo(' - ' . 'orders' . PHP_EOL);
        echo(' - ' . 'order_courses' . PHP_EOL);
        echo(' - ' . 'order_listener_tests' . PHP_EOL);
    }

}
