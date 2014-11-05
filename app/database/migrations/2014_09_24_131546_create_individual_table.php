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
                $table->string('fio_rod',160)->nullable();
                $table->string('passport_seria',10)->nullable();
                $table->string('passport_number',10)->nullable();
                $table->string('passport_data',200)->nullable();
                $table->string('passport_date',20)->nullable();
                $table->string('code',50)->nullable();
                $table->string('postaddress',255)->nullable();
                $table->string('phone',40)->nullable();
                $table->tinyInteger('discount')->default(0)->nullable();
                $table->boolean('moderator_approve')->default(0)->nullable();
                $table->timestamps();
            });

            DB::statement("CREATE VIEW users_individuals AS SELECT users.id, users.email, users.active, users.created_at,individuals.id as individual_id, individuals.fio, individuals.fio_rod, individuals.passport_seria, individuals.passport_number, individuals.passport_data, individuals.passport_date, individuals.code, individuals.postaddress, individuals.phone, individuals.discount, individuals.moderator_approve FROM users LEFT JOIN individuals ON users.id = individuals.user_id WHERE users.group_id = 6");

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
