<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserDownloadLectures extends Migration {

    public function up(){
        if (!Schema::hasTable('user_download_lectures')) {
            Schema::create('user_download_lectures', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned()->nullable()->index();
                $table->integer('lecture_id')->unsigned()->nullable()->index();
                $table->timestamps();
            });
            echo(' + ' . 'user_download_lectures' . PHP_EOL);
        } else {
            echo('...' . 'user_download_lectures' . PHP_EOL);
        }
    }

    public function down(){
        Schema::dropIfExists('user_download_lectures');
        echo(' - ' . 'user_download_lectures' . PHP_EOL);
    }
}