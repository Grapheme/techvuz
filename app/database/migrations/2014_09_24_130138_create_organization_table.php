<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrganizationTable extends Migration {

    public function up(){
        if (!Schema::hasTable('organizations')) {
            Schema::create('organizations', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->default(0)->nullable()->unsigned()->index();
                $table->string('title',255)->nullable();
                $table->string('fio_manager',160)->nullable();
                $table->string('manager',100)->nullable();
                $table->string('statutory',160)->nullable();
                $table->string('inn',40)->nullable();
                $table->string('kpp',40)->nullable();
                $table->string('postaddress',255)->nullable();
                $table->smallInteger('account_type')->default(0)->nullable()->unsigned()->index();
                $table->string('account_number',40)->nullable();
                $table->string('bank',255)->nullable();
                $table->string('bik',40)->nullable();
                $table->string('name',100)->nullable();
                $table->string('phone',40)->nullable();
                $table->timestamps();
            });

            DB::statement("CREATE VIEW users_organizations AS SELECT users.id, users.email, users.active, users.created_at,organizations.id as organization_id, organizations.title, organizations.fio_manager, organizations.manager, organizations.statutory, organizations.inn, organizations.kpp, organizations.postaddress, organizations.account_type as account_type_id, organizations.account_number, organizations.bank, organizations.bik, organizations.name, organizations.phone, account_types.title as account_type FROM organizations LEFT JOIN users ON users.id = organizations.user_id INNER JOIN account_types ON organizations.account_type = account_types.id WHERE users.group_id = 4");

            echo(' + ' . 'organizations' . PHP_EOL);
        } else {
            echo('...' . 'organizations' . PHP_EOL);
        }
    }

    public function down(){
        Schema::dropIfExists('organizations');
        DB::statement('DROP VIEW users_organizations');
        echo(' - ' . 'organizations' . PHP_EOL);
        echo(' - ' . 'users_organizations' . PHP_EOL);
    }

}
