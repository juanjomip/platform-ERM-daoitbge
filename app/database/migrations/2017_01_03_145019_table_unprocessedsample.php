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
		Schema::create('user', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('username');
            $table->string('password');
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);                      
                   
        });

        Schema::create('unprocessed_sample', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('user');
            $table->integer('value');
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);   
            $table->integer('cell_id');         
            $table->date('date')->nullable()->default(null);
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
            
        });

        Schema::create('cell_path', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('cell_id')->unsigned()->nullable();
            $table->foreign('cell_id')->references('id')->on('cell');           
            $table->decimal('lat', 10,7);
            $table->decimal('lng', 10,7);            
            $table->integer('utm_x');
            $table->integer('utm_y');            
        });

        Schema::create('polygon', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->decimal('center_lat', 10,7);
            $table->decimal('center_lng', 10,7);        
        });

        Schema::create('polygon_path', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('polygon_id')->unsigned()->nullable();
            $table->foreign('polygon_id')->references('id')->on('polygon');           
            $table->decimal('lat', 10,7);
            $table->decimal('lng', 10,7);          
        });

        Schema::create('sensitive_area', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->decimal('center_lat', 10,7);
            $table->decimal('center_lng', 10,7);
            $table->integer('radio');
        });

        Schema::create('cell_polygon', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('cell_id')->unsigned()->nullable();
            $table->foreign('cell_id')->references('id')->on('cell');
            $table->integer('polygon_id')->unsigned()->nullable();
            $table->foreign('polygon_id')->references('id')->on('polygon');                  
        });

        Schema::create('cell_measurement', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');           
            $table->integer('cell_id')->unsigned()->nullable();
            $table->foreign('cell_id')->references('id')->on('cell');   
            $table->date('date');
            $table->integer('value');
            $table->integer('quantity')->default(0);                  
        });

        Schema::create('polygon_measurement', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('polygon_id')->unsigned()->nullable();
            $table->foreign('polygon_id')->references('id')->on('polygon');
            $table->date('date');
            $table->integer('value');
            $table->integer('quantity')->default(0);                   
                   
        });

        Schema::create('sensitive_area_measurement', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');          
            $table->integer('sensitive_area_id')->unsigned()->nullable();
            $table->foreign('sensitive_area_id')->references('id')->on('sensitive_area');
            $table->date('date');
            $table->integer('value');            
                   
        });

        // Start Seed.

        Polygon::santiagoFromKml();

        /*$paths = CommunePath::all();
        foreach ($paths as $path) {
            $sample = new UnprocessedSample();
            $sample->lat = $path->lat;
            $sample->lng = $path->lng;
            $sample->save();
            $sample->assignCell();
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
