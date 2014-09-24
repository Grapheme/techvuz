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
