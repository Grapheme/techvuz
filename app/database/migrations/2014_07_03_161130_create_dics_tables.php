<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDicsTables extends Migration {

	public function up(){

        $this->table = "dictionary";
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function(Blueprint $table) {
                $table->increments('id');
                $table->string('slug')->nullable()->unique();
                $table->string('name')->nullable();
    			$table->boolean('entity')->unsigned()->nullable()->index();
                $table->string('icon_class')->nullable();
    			$table->boolean('hide_slug')->unsigned()->nullable();
    			$table->string('name_title')->nullable();
                $table->integer('order')->unsigned()->nullable()->index();
            });
            echo(' + ' . $this->table . PHP_EOL);
        } else {
            echo('...' . $this->table . PHP_EOL);
        }

        $this->table = "dictionary_values";
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function(Blueprint $table) {
                $table->increments('id');
                $table->integer('dic_id')->unsigned()->nullable()->index();
                $table->string('slug')->nullable()->index();
                $table->string('name')->nullable();
                $table->integer('order')->unsigned()->nullable()->index();
            });
            echo(' + ' . $this->table . PHP_EOL);
        } else {
            echo('...' . $this->table . PHP_EOL);
        }

        $this->table = "dictionary_fields_values";
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function(Blueprint $table) {
                $table->increments('id');
                $table->integer('dicval_id')->unsigned()->nullable()->index();
                $table->string('language', 16)->nullable()->index();
                $table->string('key')->nullable()->index();
                $table->string('value')->nullable();
            });
            echo(' + ' . $this->table . PHP_EOL);
        } else {
            echo('...' . $this->table . PHP_EOL);
        }

        $this->table = "dictionary_values_meta";
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function(Blueprint $table) {
                $table->increments('id');
                $table->integer('dicval_id')->unsigned()->nullable()->index();
                $table->string('language', 16)->nullable()->index();
                $table->string('name')->nullable();
            });
            echo(' + ' . $this->table . PHP_EOL);
        } else {
            echo('...' . $this->table . PHP_EOL);
        }

    }


	public function down(){

        Schema::dropIfExists('dictionary');
        echo(' - ' . 'dictionary' . PHP_EOL);

        Schema::dropIfExists('dictionary_values');
        echo(' - ' . 'dictionary_values' . PHP_EOL);

        Schema::dropIfExists('dictionary_values_meta');
        echo(' - ' . 'dictionary_values_meta' . PHP_EOL);

        Schema::dropIfExists('dictionary_fields_values');
        echo(' - ' . 'dictionary_fields_values' . PHP_EOL);
	}

}

