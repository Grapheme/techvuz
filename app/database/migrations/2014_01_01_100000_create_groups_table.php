<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGroupsTable extends Migration {

    public $table = 'groups';

	public function up(){
        if (!Schema::hasTable($this->table)) {
        	Schema::create($this->table, function(Blueprint $table) {			
        		$table->increments('id');
        		$table->string('name',20)->unique();
        		$table->string('desc',20);
        		$table->string('dashboard',20);
        		$table->string('start_url', 256);
        		$table->timestamps();
        	});
            echo(' + ' . $this->table . PHP_EOL);
        } else
            echo('...' . $this->table . PHP_EOL);
	}

	public function down(){
		Schema::dropIfExists($this->table);
        echo(' - ' . $this->table . PHP_EOL);
	}

}
