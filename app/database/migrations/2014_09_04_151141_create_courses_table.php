<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCoursesTable extends Migration {

    public function up(){
        if (!Schema::hasTable('courses')) {
            Schema::create('courses', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('direction_id')->unsigned()->nullable()->index();
                $table->integer('sort')->default(0)->unsigned()->nullable();
                $table->string('code',10)->nullable();
                $table->string('title',255)->nullable();
                $table->text('description')->nullable();
                $table->float('price')->default(0)->unsigned()->nullable();
                $table->integer('hours')->default(0)->unsigned()->nullable();
                $table->string('libraries',100)->nullable();
                $table->string('curriculum',100)->nullable();
                $table->string('metodical',100)->nullable();
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
