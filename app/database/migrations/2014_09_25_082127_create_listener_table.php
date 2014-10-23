<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateListenerTable extends Migration {

    public function up(){
        if (!Schema::hasTable('listeners')) {
            Schema::create('listeners', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->default(0)->nullable()->unsigned()->index();
                $table->integer('organization_id')->default(0)->nullable()->unsigned()->index();
                $table->string('fio',160)->nullable();
                $table->string('position',100)->nullable();
                $table->string('postaddress',255)->nullable();
                $table->string('phone',40)->nullable();
                $table->string('education',100)->nullable();
                $table->string('place_work',100)->nullable();
                $table->string('year_study',4)->nullable();
                $table->string('specialty',100)->nullable();
                $table->timestamps();
            });

            DB::statement("CREATE VIEW users_listeners AS SELECT users.id, users.email, users.active, users.created_at,listeners.id as listener_id, listeners.organization_id, listeners.fio, listeners.position, listeners.postaddress, listeners.phone, listeners.education, listeners.place_work, listeners.year_study, listeners.specialty FROM listeners LEFT JOIN users ON users.id = listeners.user_id WHERE users.group_id = 5");

            echo(' + ' . 'listeners' . PHP_EOL);
        } else {
            echo('...' . 'listeners' . PHP_EOL);
        }
    }

    public function down(){
        Schema::dropIfExists('listeners');
        DB::statement('DROP VIEW users_listeners');
        echo(' - ' . 'listeners' . PHP_EOL);
        echo(' - ' . 'users_listeners' . PHP_EOL);
    }

}
