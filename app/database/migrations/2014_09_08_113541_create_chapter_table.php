<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChapterTable extends Migration {

    public function up(){
        if (!Schema::hasTable('chapters')) {
            Schema::create('chapters', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('course_id')->unsigned()->nullable()->index();
                $table->integer('order')->unsigned()->nullable();
                $table->string('title',255)->nullable();
                $table->string('test_title',255)->nullable();
                $table->integer('test_hours')->default(0)->unsigned()->nullable();
                $table->text('description')->nullable();
                $table->integer('hours')->default(0)->unsigned()->nullable();
                $table->timestamps();
            });
            echo(' + ' . 'chapters' . PHP_EOL);
        } else {
            echo('...' . 'chapters' . PHP_EOL);
        }
    }

    public function down(){
        Schema::dropIfExists('chapters');
        echo(' - ' . 'chapters' . PHP_EOL);
    }

}
