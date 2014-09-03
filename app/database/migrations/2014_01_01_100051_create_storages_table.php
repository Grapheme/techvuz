<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStoragesTable extends Migration {

    public $table = 'storages';

	public function up(){
        if (!Schema::hasTable($this->table)) {
        	Schema::create($this->table, function(Blueprint $table) {			
    			$table->increments('id');
    			$table->string('name');
    			$table->text('value');
    			$table->timestamps();
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