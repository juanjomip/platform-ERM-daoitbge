<?php

class UNprocessedSample extends Eloquent {
	
	protected $table = 'unprocessed_sample';

	public $timestamps = false;
	
	protected $fillable = array('lat', 'lng', 'value', 'datetime');

}
