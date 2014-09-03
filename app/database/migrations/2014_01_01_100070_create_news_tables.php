<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewsTables extends Migration {

    public $table1 = 'news';
    public $table2 = 'news_meta';

	public function up(){
        if (!Schema::hasTable($this->table1)) {
    		Schema::create($this->table1, function(Blueprint $table) {
    			$table->increments('id');
                $table->string('slug',64)->nullable();
                $table->string('template',100)->nullable();
                $table->integer('type_id')->unsigned()->nullable()->index();
                $table->boolean('publication')->unsigned()->nullable()->default(1)->index();
    			$table->timestamps();
                $table->date('published_at')->index();
    		});
            echo(' + ' . $this->table1 . PHP_EOL);
        } else {
            echo('...' . $this->table1 . PHP_EOL);
        }

        if (!Schema::hasTable($this->table2)) {
    		Schema::create($this->table2, function(Blueprint $table) {
                $table->increments('id');
                $table->integer('news_id')->unsigned()->nullable()->index();
                $table->string('language', 16)->nullable()->index();
                $table->string('title', 128)->nullable();
    			$table->text('preview')->nullable();
    			$table->mediumText('content')->nullable();
                $table->integer('photo_id')->unsigned()->nullable();
                $table->integer('gallery_id')->unsigned()->nullable();
    			$table->timestamps();
    		});
            echo(' + ' . $this->table2 . PHP_EOL);
        } else {
            echo('...' . $this->table2 . PHP_EOL);
        }
	}

	public function down(){
		Schema::dropIfExists($this->table1);
        echo(' - ' . $this->table1 . PHP_EOL);

		Schema::dropIfExists($this->table2);
        echo(' - ' . $this->table2 . PHP_EOL);
	}

}