<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePasswordRemindersTable extends Migration {

	public function up(){

        if (!Schema::hasTable('password_reminders')) {
            Schema::create('password_reminders', function(Blueprint $table) {
                $table->string('email')->index();
                $table->string('token')->index();
                $table->timestamp('created_at');
            });
            echo(' + ' . 'password_reminders' . PHP_EOL);
        } else {
            echo('...' . 'password_reminders' . PHP_EOL);
        }
	}

    public function down(){
        Schema::dropIfExists('password_reminders');
        echo(' - ' . 'password_reminders' . PHP_EOL);
    }
}
