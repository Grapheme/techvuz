<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTestsTable extends Migration {

    public function up(){
        if (!Schema::hasTable('users_tests')) {
            Schema::create('users_tests', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned()->nullable()->index();
                $table->integer('test_id')->unsigned()->nullable()->index();
                $table->integer('attempt')->default(0)->unsigned()->nullable();
                $table->integer('result')->default(0)->unsigned()->nullable();
                $table->integer('time')->default(0)->unsigned()->nullable();
                $table->timestamps();
            });
            echo(' + ' . 'users_tests' . PHP_EOL);
        } else {
            echo('...' . 'users_tests' . PHP_EOL);
        }
    }

    public function down(){
        Schema::dropIfExists('users_tests');
        echo(' - ' . 'users_tests' . PHP_EOL);
    }

}
