<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTestsQuestionsTable extends Migration {

    public function up(){
        if (!Schema::hasTable('tests_questions')) {
            Schema::create('tests_questions', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('test_id')->unsigned()->nullable()->index();
                $table->integer('order')->unsigned()->nullable();
                $table->string('title',255)->nullable();
                $table->text('description')->nullable();
                $table->text('comment')->nullable();
                $table->timestamps();
            });
            echo(' + ' . 'tests_questions' . PHP_EOL);
        } else {
            echo('...' . 'tests_questions' . PHP_EOL);
        }
    }

    public function down(){
        Schema::dropIfExists('tests_questions');
        echo(' - ' . 'tests_questions' . PHP_EOL);
    }

}
