<?php

class UNprocessedSample extends Eloquent {
	
	protected $table = 'unprocessed_sample';
	
	protected $fillable = array('lat', 'lng', 'value', 'timestamp');

}
