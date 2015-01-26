<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDirectionsTable extends Migration {

    public function up(){
        if (!Schema::hasTable('directions')) {
            Schema::create('directions', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('order')->unsigned()->nullable();
                $table->string('code',10)->nullable();
                $table->string('title',255)->nullable();
                $table->integer('photo_id')->unsigned()->nullable();
                $table->text('description')->nullable();
                $table->tinyInteger('discount')->default(0)->nullable();
                $table->boolean('use_discount')->default(1)->nullable();
                $table->boolean('active')->default(0)->unsigned()->nullable();
                $table->boolean('in_progress')->default(0)->unsigned()->nullable();
                $table->timestamps();
            });
            echo(' + ' . 'directions' . PHP_EOL);
        } else {
            echo('...' . 'directions' . PHP_EOL);
        }
    }

    public function down(){
        Schema::dropIfExists('directions');
        echo(' - ' . 'directions' . PHP_EOL);
    }

}
