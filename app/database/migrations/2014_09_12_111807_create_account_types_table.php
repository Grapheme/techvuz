<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountTypesTable extends Migration {

    public function up(){
        if (!Schema::hasTable('account_types')) {
            Schema::create('account_types', function(Blueprint $table) {
                $table->increments('id');
                $table->string('title',50)->nullable();
                $table->timestamps();
            });
            echo(' + ' . 'account_types' . PHP_EOL);
        } else {
            echo('...' . 'account_types' . PHP_EOL);
        }
    }

    public function down(){
        Schema::dropIfExists('account_types');
        echo(' - ' . 'account_types' . PHP_EOL);
    }

}

