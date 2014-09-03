<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRelModGalleryTable extends Migration {

    public $table = 'rel_mod_gallery';

	public function up(){
        if (!Schema::hasTable($this->table)) {
    		Schema::create($this->table, function(Blueprint $table) {
    			$table->increments('id');
                $table->string('module', 16)->nullable();
                $table->integer('unit_id')->default(0)->unsigned()->nullable();
                $table->integer('gallery_id')->default(0)->unsigned()->nullable();
           		$table->index('module');
           		$table->index('module', 'unit_id', 'gallery_id');
           		//$table->index('gallery_id');
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