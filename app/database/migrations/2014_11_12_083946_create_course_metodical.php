<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCourseMetodical extends Migration {

    public function up(){
        if (!Schema::hasTable('course_metodical')) {
            Schema::create('course_metodical', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('course_id')->unsigned()->nullable()->index();
                $table->integer('order')->unsigned()->nullable();
                $table->string('title',255)->nullable();
                $table->text('description')->nullable();
                $table->integer('document_id')->default(0)->unsigned()->nullable();
                $table->timestamps();
            });
            echo(' + ' . 'course_metodical' . PHP_EOL);
        } else {
            echo('...' . 'course_metodical' . PHP_EOL);
        }
    }

    public function down(){
        Schema::dropIfExists('course_metodical');
        echo(' - ' . 'course_metodical' . PHP_EOL);
    }
}
