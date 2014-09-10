<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVideoTables extends Migration {

	public function up(){

        $this->table = "videos";
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function(Blueprint $table) {
                $table->increments('id');
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->text('embed')->nullable();
                $table->integer('image_id')->nullable();
                $table->string('module', 32)->nullable()->index();
                $table->integer('unit_id')->unsigned()->nullable()->index();
                $table->timestamps();
            });
            echo(' + ' . $this->table . PHP_EOL);
        } else {
            echo('...' . $this->table . PHP_EOL);
        }

    }


	public function down(){

        Schema::dropIfExists('videos');
        echo(' - ' . 'videos' . PHP_EOL);

	}

}

