<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSeoTable extends Migration {

    public $table = 'seo';

	public function up(){
        if (!Schema::hasTable($this->table)) {
    		Schema::create($this->table, function(Blueprint $table) {

    			$table->increments('id');

                $table->string('module', 32)->nullable()->index();
                $table->integer('unit_id')->unsigned()->nullable()->index();
                $table->string('language', 16)->nullable()->index();

                /*
                ALTER TABLE `seo` ADD `language` VARCHAR( 16 ) NULL AFTER `unit_id`;
                ALTER TABLE `seo` ADD INDEX ( `language` );
                */

                $table->string('title', 256)->nullable();
                $table->text('description')->nullable();
                $table->text('keywords')->nullable();
                $table->string('url', 256)->nullable()->index();
                $table->string('h1', 256)->nullable();
    			$table->timestamps();

           		$table->index('module', 'unit_id');
    		});
            echo(' + ' . $this->table . PHP_EOL);
        } else {
            echo('...' . $this->table . PHP_EOL);
        }
	}

	public function down(){
		Schema::dropIfExists($this->table);
        echo(' - ' . $this->table . PHP_EOL);
	}

}