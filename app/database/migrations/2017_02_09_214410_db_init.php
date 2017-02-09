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
	        $table->integer('user_id')->unsigned()->nullable();
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
            $table->integer('index_x');
            $table->integer('index_y');
            $table->decimal('center_lat', 10,7);
            $table->decimal('center_lng', 10,7);
            $table->string('utm_lat_zone');
            $table->integer('utm_lng_zone');           
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
            $table->integer('polygon_id')->unsigned()->nullable();
            $table->foreign('polygon_id')->references('id')->on('polygon');         
            $table->decimal('lat', 10,7);
            $table->decimal('lng', 10,7);          
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
                   
        });
		
	    Schema::create('polygon_measurement', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
	        $table->integer('polygon_id')->unsigned()->nullable();
            $table->foreign('polygon_id')->references('id')->on('polygon');
            $table->date('date');
            $table->integer('value');            
                   
        });
		
	    Schema::create('sensitive_area_measurement', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');	       
            $table->integer('sensitive_area_id')->unsigned()->nullable();
            $table->foreign('sensitive_area_id')->references('id')->on('sensitive_area');
            $table->date('date');
            $table->integer('value');            
                   
        });

        UTMCell::makeDefaultCells();
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
        Schema::drop('cell_path');
		Schema::drop('cell');
        Schema::drop('sensitive_area');
		Schema::drop('sample');
		Schema::drop('user');
	}

}
