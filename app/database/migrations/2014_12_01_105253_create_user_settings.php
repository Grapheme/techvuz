<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserSettings extends Migration {

    public function up(){
        if (!Schema::hasTable('user_settings')) {
            Schema::create('user_settings', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned()->nullable()->index();
                $table->string('slug',50)->nullable();
                $table->string('value',50)->nullable();
                $table->timestamps();
            });
            echo(' + ' . 'user_settings' . PHP_EOL);
        } else {
            echo('...' . 'user_settings' . PHP_EOL);
        }
    }

    public function down(){
        Schema::dropIfExists('user_settings');
        echo(' - ' . 'user_settings' . PHP_EOL);
    }
}

