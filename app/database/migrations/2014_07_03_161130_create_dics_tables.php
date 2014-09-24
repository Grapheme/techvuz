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
                $table->string('name')->nullable()->index();
    			$table->boolean('entity')->unsigned()->nullable()->index();
                $table->string('icon_class')->nullable();
    			$table->boolean('hide_slug')->unsigned()->nullable();
    			$table->boolean('make_slug_from_name')->unsigned()->nullable();
    			$table->string('name_title')->nullable();

                $table->integer('pagination')->unsigned()->default(0);
                $table->smallInteger('view_access')->unsigned()->nullable()->index();
    			$table->string('sort_by', 64)->nullable();
    			$table->smallInteger('sort_order_reverse')->unsigned()->default(0);
                $table->smallInteger('sortable')->unsigned()->default(1);
/*
ALTER TABLE `dictionary` ADD `pagination` INT( 11 ) NOT NULL DEFAULT '0' AFTER `name_title` ,
ADD `view_access` INT( 1 ) NULL AFTER `pagination` ,
ADD `sort_by` VARCHAR( 64 ) NULL AFTER `view_access` ,
ADD `draggable` INT( 1 ) NOT NULL DEFAULT '1' AFTER `sort_by`
ADD `sort_order` ENUM( 'ASC', 'DESC' ) NOT NULL DEFAULT 'ASC' AFTER `sort_by`
*/
                $table->integer('order')->unsigned()->nullable()->index();
    			$table->timestamps();
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
    			$table->timestamps();
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
                $table->text('value')->nullable();
    			$table->timestamps();
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
    			$table->timestamps();
            });
            echo(' + ' . $this->table . PHP_EOL);
        } else {
            echo('...' . $this->table . PHP_EOL);
        }

        $this->table = "dictionary_values_rel";
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function(Blueprint $table) {
                $table->integer('dicval_parent_id')->unsigned()->nullable()->index();
                $table->integer('dicval_child_id')->unsigned()->nullable()->index();
                $table->string('dicval_child_dic', 256)->nullable()->index();
                $table->primary(array('dicval_parent_id', 'dicval_child_id'));
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

        Schema::dropIfExists('dictionary_values_rel');
        echo(' - ' . 'dictionary_values_rel' . PHP_EOL);
	}

}

