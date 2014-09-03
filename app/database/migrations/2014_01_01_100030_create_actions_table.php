<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActionsTable extends Migration {

    public $table = 'actions';

	public function up(){
        if (!Schema::hasTable($this->table)) {
    		Schema::create($this->table, function(Blueprint $table) {			
    			$table->increments('id');
    			$table->integer('group_id')->nullable()->unsigned()->index();
    			$table->string('module', 32)->nullable()->index();
    			$table->string('action', 32)->nullable()->index();
    			$table->integer('status')->default(0)->unsigned()->index();
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
