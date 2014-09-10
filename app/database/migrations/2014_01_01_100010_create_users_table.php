<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

    public $table = 'users';

	public function up(){
        if (!Schema::hasTable($this->table)) {
    		Schema::create($this->table, function(Blueprint $table) {			
    			$table->increments('id');
    			$table->smallInteger('group_id')->default(0)->nullable()->unsigned();
    			$table->string('name',100)->nullable();
    			$table->string('surname',100)->nullable();
    			$table->string('email',100)->unique()->nullable();
    			$table->smallInteger('active')->default(0)->nullable()->unsigned();
    			$table->string('password',60)->nullable();
    			$table->string('photo',100)->nullable();
    			$table->string('thumbnail',100)->nullable();
    			$table->string('temporary_code',25)->nullable();
    			$table->bigInteger('code_life')->nullable();
    			$table->string('remember_token',100)->nullable();
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
