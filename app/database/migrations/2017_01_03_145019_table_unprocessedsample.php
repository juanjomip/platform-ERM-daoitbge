<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableUnprocessedsample extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('unprocessed_sample', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('value');
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);   
            $table->integer('cell_code');         
            $table->datetime('datetime')->nullable()->default(null);
        });
       

        Schema::create('cell', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('lat_index');
            $table->integer('lng_index');
            $table->decimal('bottom_left_lat', 10,7);
            $table->decimal('bottom_left_lng', 10,7);
            $table->decimal('top_left_lat', 10,7);
            $table->decimal('top_left_lng', 10,7);
            $table->decimal('top_right_lat', 10,7);
            $table->decimal('top_right_lng', 10,7);
            $table->decimal('bottom_right_lat', 10,7);
            $table->decimal('bottom_right_lng', 10,7);
            
        });

        Schema::create('commune', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->decimal('center_lat', 10,7);
            $table->decimal('center_lng', 10,7);        
        });

        Schema::create('commune_path', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('commune_id');            
            $table->decimal('lat', 10,7);
            $table->decimal('lng', 10,7);          
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('commune_path');
        Schema::drop('commune');
        Schema::drop('cell');
		Schema::drop('unprocessed_sample');
	}

}
