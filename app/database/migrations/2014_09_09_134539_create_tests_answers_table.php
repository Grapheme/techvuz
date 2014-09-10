<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTestsAnswersTable extends Migration {

    public function up(){
        if (!Schema::hasTable('tests_answers')) {
            Schema::create('tests_answers', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('test_id')->unsigned()->nullable()->index();
                $table->integer('test_question_id')->unsigned()->nullable()->index();
                $table->integer('order')->unsigned()->nullable();
                $table->string('title',255)->nullable();
                $table->text('description')->nullable();
                $table->integer('correct')->default(0)->unsigned()->nullable();
                $table->timestamps();
            });
            echo(' + ' . 'tests_answers' . PHP_EOL);
        } else {
            echo('...' . 'tests_answers' . PHP_EOL);
        }
    }

    public function down(){
        Schema::dropIfExists('tests_answers');
        echo(' - ' . 'tests_answers' . PHP_EOL);
    }

}
