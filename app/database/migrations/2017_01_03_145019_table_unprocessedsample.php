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
		/*Schema::create('unprocessed_sample', function ($table) {
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
            $table->decimal('center_lat', 10,7);
            $table->decimal('center_lng', 10,7);
            
        });*

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

        Schema::create('cell_commune', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('cell_id');
            $table->integer('commune_id');            
                   
        });*/
		
	//
		
	
		
	   

        // Start Seed.

        //Commune::santiagoFromKml();

        /*$paths = CommunePath::all();
        foreach ($paths as $path) {
            $sample = new UnprocessedSample();
            $sample->lat = $path->lat;
            $sample->lng = $path->lng;
            $sample->save();
            $sample->assignCell();
        }*/

        //33.3633808,-70.5640598 top right
        
        // bottom left -33.498147,-70.7384529,12.17

        //                      culador-18571  -39277
        // -18646  -39374 


        /*for ($i=-18750; $i < -18530; $i++) {            
            for ($j=-39450; $j < -39210; $j++) { 
                Cell::createAndConfig($i, $j);
            }
        }*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('cell_commune');
        Schema::drop('commune_path');
        Schema::drop('commune');
        Schema::drop('cell');
		Schema::drop('unprocessed_sample');
	}

}
