<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTestsTable extends Migration {

    public function up(){
        if (!Schema::hasTable('tests')) {
            Schema::create('tests', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('course_id')->unsigned()->nullable()->index();
                $table->integer('chapter_id')->unsigned()->nullable()->index();
                $table->integer('order')->unsigned()->nullable();
                $table->string('title',255)->nullable();
                $table->text('description')->nullable();
                $table->integer('active')->default(0)->unsigned()->nullable();
                $table->timestamps();
            });
            echo(' + ' . 'tests' . PHP_EOL);
        } else {
            echo('...' . 'tests' . PHP_EOL);
        }
    }

    public function down(){
        Schema::dropIfExists('tests');
        echo(' - ' . 'tests' . PHP_EOL);
    }

}
