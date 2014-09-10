<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePagesTables extends Migration {

    public $table0 = 'pages';
    public $table1 = 'pages_meta';
    public $table2 = 'pages_blocks';
    public $table3 = 'pages_blocks_meta';

	public function up(){
        if (!Schema::hasTable($this->table0)) {
    		Schema::create($this->table0, function(Blueprint $table) {
                $table->increments('id');

                $table->string('name', 128)->nullable();
                $table->string('slug', 128)->nullable()->index();
                $table->string('template', 128)->nullable();
                $table->integer('type_id')->unsigned()->nullable()->index();

                $table->boolean('publication')->default(1)->unsigned()->nullable()->index();
    			$table->boolean('start_page')->unsigned()->nullable()->index();
                $table->boolean('in_menu')->unsigned()->nullable()->index();
                $table->integer('order')->unsigned()->nullable()->index();

    			$table->timestamps();
     		});
            echo(' + ' . $this->table0 . PHP_EOL);
        } else {
            echo('...' . $this->table0 . PHP_EOL);
        }

        if (!Schema::hasTable($this->table1)) {
    		Schema::create($this->table1, function(Blueprint $table) {
                $table->increments('id');
                $table->integer('page_id')->unsigned()->nullable()->index();
                $table->string('language', 16)->nullable()->index();

                $table->string('template', 128)->nullable();

    			$table->timestamps();
     		});
            echo(' + ' . $this->table1 . PHP_EOL);
        } else {
            echo('...' . $this->table1 . PHP_EOL);
        }

        if (!Schema::hasTable($this->table2)) {
    		Schema::create($this->table2, function(Blueprint $table) {
                $table->increments('id');
                $table->integer('page_id')->unsigned()->nullable()->index();

                $table->string('name', 128)->nullable();
                $table->string('slug', 128)->nullable()->index();
                $table->string('desc', 128)->nullable();
                $table->string('template', 128)->nullable();
                $table->integer('order')->unsigned()->nullable()->index();

    			$table->timestamps();
    		});
            echo(' + ' . $this->table2 . PHP_EOL);
        } else {
            echo('...' . $this->table2 . PHP_EOL);
        }

        if (!Schema::hasTable($this->table3)) {
    		Schema::create($this->table3, function(Blueprint $table) {
                $table->increments('id');
                $table->integer('block_id')->unsigned()->nullable()->index();

                $table->string('name', 128)->nullable();
    			$table->text('content')->nullable();
                $table->string('language', 16)->nullable()->index();
                $table->string('template', 128)->nullable();

    			$table->timestamps();
    		});
            echo(' + ' . $this->table3 . PHP_EOL);
        } else {
            echo('...' . $this->table3 . PHP_EOL);
        }
	}

	public function down(){

		Schema::dropIfExists($this->table0);
        echo(' - ' . $this->table0 . PHP_EOL);

		Schema::dropIfExists($this->table1);
        echo(' - ' . $this->table1 . PHP_EOL);

		Schema::dropIfExists($this->table2);
        echo(' - ' . $this->table2 . PHP_EOL);

		Schema::dropIfExists($this->table3);
        echo(' - ' . $this->table3 . PHP_EOL);
	}

}