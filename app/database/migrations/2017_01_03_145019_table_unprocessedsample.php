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
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('unprocessed_sample');
	}

}
