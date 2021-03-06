<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCoursesTable extends Migration {

    public function up(){
        if (!Schema::hasTable('courses')) {
            Schema::create('courses', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('direction_id')->unsigned()->nullable()->index();
                $table->integer('order')->unsigned()->nullable();
                $table->string('code',10)->nullable();
                $table->string('title',255)->nullable();
                $table->string('test_title',255)->nullable();
                $table->integer('test_questions_count')->default(0)->unsigned()->nullable();
                $table->integer('test_hours')->default(0)->unsigned()->nullable();
                $table->text('description')->nullable();
                $table->text('curriculum')->nullable();
                $table->float('price')->default(2)->unsigned()->nullable();
                $table->tinyInteger('discount')->default(0)->nullable();
                $table->boolean('use_discount')->default(1)->nullable();
                $table->integer('hours')->default(0)->unsigned()->nullable();
                $table->integer('certificate')->default(0)->unsigned()->nullable();
                $table->boolean('active')->default(0)->unsigned()->nullable();
                $table->boolean('in_progress')->default(0)->unsigned()->nullable();
                $table->timestamps();
            });
            echo(' + ' . 'courses' . PHP_EOL);
        } else {
            echo('...' . 'courses' . PHP_EOL);
        }
    }

    public function down(){
        Schema::dropIfExists('courses');
        echo(' - ' . 'courses' . PHP_EOL);
    }

}
