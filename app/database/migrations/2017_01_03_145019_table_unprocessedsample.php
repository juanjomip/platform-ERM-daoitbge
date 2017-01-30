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
            $table->datetime('datetime')->nullable()->default(null);
        });

        DB::table('unprocessed_sample')->insert(
        	array(
        		array(
        			'value' => 1,
        			'lat' => 2,
        			'lng' => 3
        		),
        		array(
        			'value' => 1,
        			'lat' => 2,
        			'lng' => 3
        		),
        		array(
        			'value' => 1,
        			'lat' => 2,
        			'lng' => 3
        		),
        		array(
        			'value' => 1,
        			'lat' => 2,
        			'lng' => 3
        		),
        		array(
        			'value' => 1,
        			'lat' => 2,
        			'lng' => 3
        		)
        	)
        );

        Schema::create('cell', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->decimal('lat_index', 10,7);
            $table->decimal('lng_index', 10,7);
            $table->decimal('bottom_left_lat', 10,7);
            $table->decimal('bottom_left_lng', 10,7);
            $table->decimal('top_left_lat', 10,7);
            $table->decimal('top_left_lng', 10,7);
            $table->decimal('top_right_lat', 10,7);
            $table->decimal('top_right_lng', 10,7);
            $table->decimal('bottom_left_lat', 10,7);
            $table->decimal('bottom_left_lng', 10,7);
            $table->integer('lat_index');
            $table->integer('lng_index');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('cell');
		Schema::drop('unprocessed_sample');
	}

}
