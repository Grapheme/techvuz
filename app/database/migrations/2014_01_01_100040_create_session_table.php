<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSessionTable extends Migration {

    public $table = 'sessions';

	public function up(){
        if (!Schema::hasTable($this->table)) {
        	Schema::create($this->table, function(Blueprint $table) {			
    			$table->string('id')->unique();
    			$table->text('payload');
    			$table->integer('last_activity');
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
