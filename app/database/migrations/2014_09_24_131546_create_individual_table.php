<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIndividualTable extends Migration {

    public function up(){
        if (!Schema::hasTable('individuals')) {
            Schema::create('individuals', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->default(0)->nullable()->unsigned()->index();
                $table->string('fio',160)->nullable();
                $table->string('position',100)->nullable();
                $table->string('inn',40)->nullable();
                $table->string('postaddress',255)->nullable();
                $table->string('phone',40)->nullable();
                $table->timestamps();
            });

            DB::statement("CREATE VIEW users_individuals AS SELECT users.id, users.email, users.active, users.created_at,individuals.id as individual_id, individuals.fio, individuals.position, individuals.inn, individuals.postaddress, individuals.phone FROM users LEFT JOIN individuals ON users.id = individuals.user_id WHERE users.group_id = 6");

            echo(' + ' . 'individuals' . PHP_EOL);
        } else {
            echo('...' . 'individuals' . PHP_EOL);
        }
    }

    public function down(){
        Schema::dropIfExists('individuals');
        DB::statement('DROP VIEW users_individuals');
        echo(' - ' . 'individuals' . PHP_EOL);
        echo(' - ' . 'users_individuals' . PHP_EOL);
    }

}
