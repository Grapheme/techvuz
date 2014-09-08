<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLecturesTable extends Migration {

    public function up(){
        if (!Schema::hasTable('lectures')) {
            Schema::create('lectures', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('course_id')->unsigned()->nullable()->index();
                $table->integer('chapter_id')->unsigned()->nullable()->index();
                $table->integer('order')->unsigned()->nullable();
                $table->string('title',255)->nullable();
                $table->text('description')->nullable();
                $table->integer('document')->default(0)->unsigned()->nullable();
                $table->timestamps();
            });
            echo(' + ' . 'lectures' . PHP_EOL);
        } else {
            echo('...' . 'lectures' . PHP_EOL);
        }
    }

    public function down(){
        Schema::dropIfExists('lectures');
        echo(' - ' . 'lectures' . PHP_EOL);
    }

}
