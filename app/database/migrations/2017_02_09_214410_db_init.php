<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DbInit extends Migration {

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
		
	   Schema::create('sample', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
	        $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
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
            
        });

        Schema::create('polygon', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->decimal('center_lat', 10,7);
            $table->decimal('center_lng', 10,7);        
        });
		
	    Schema::create('sensitive_area', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->decimal('center_lat', 10,7);
            $table->decimal('center_lng', 10,7);
	        $table->integer('radio');
        });

        Schema::create('polygon_path', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('polygon_id');
            $table->foreign('polygon_id')->references('id')->on('polygon');         
            $table->decimal('lat', 10,7);
            $table->decimal('lng', 10,7);          
        });

        Schema::create('cell_polygon', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('cell_id');
            $table->foreign('cell_id')->references('id')->on('cell');
            $table->integer('polygon_id'); 
            $table->foreign('polygon_id')->references('id')->on('polygon');                  
        });
		
	    Schema::create('cell_measurement', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');	        
            $table->integer('cell_id');
            $table->foreign('cell_id')->references('id')->on('cell');	
            $table->date('date');
            $table->integer('value');            
                   
        });
		
	    Schema::create('polygon_measurement', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
	        $table->integer('polygon_id');
            $table->foreign('polygon_id')->references('id')->on('polygon');
            $table->date('date');
            $table->integer('value');            
                   
        });
		
	    Schema::create('sensitive_area_measurement', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');	       
            $table->integer('sensitive_area_id');
            $table->foreign('sensitive_area_id')->references('id')->on('sensitive_area');
            $table->date('date');
            $table->integer('value');            
                   
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sensitive_area_measurement');
		Schema::drop('polygon_measurement');
		Schema::drop('cell_measurement');
		Schema::drop('cell_polygon');
		Schema::drop('polygon_path');
		Schema::drop('polygon');
		Schema::drop('cell');
		Schema::drop('sample');
		Schema::drop('user');
	}

}
