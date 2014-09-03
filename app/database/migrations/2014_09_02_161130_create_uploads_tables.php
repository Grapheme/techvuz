<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUploadsTables extends Migration {

	public function up(){

        $this->table = "uploads";
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function(Blueprint $table) {
                $table->increments('id');
                $table->string('path')->nullable();
                $table->string('original_name')->nullable();
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

        Schema::dropIfExists('uploads');
        echo(' - ' . 'uploads' . PHP_EOL);

	}

}

