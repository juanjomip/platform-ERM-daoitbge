<?php

class UNprocessedSample extends Eloquent {
	
	protected $table = 'unprocessed_sample';

	public $timestamps = false;
	
	protected $fillable = array('lat', 'lng', 'value', 'datetime');

	public function assignCell(){
		$lat_index = floor($this->lat/Cell::SIDE_SIZE);
		$lng_index = floor($this->lng/Cell::SIDE_SIZE);		
		if($cell = Cell::where('lat_index', $lat_index)->where('lng_index', $lng_index)->first()) {
			$this->cell_code = $cell->id;
			$this->save();
		} else {
			$cell = Cell::createAndConfig($lat_index, $lng_index);
			$this->cell_code = $cell->id;
			$this->save();
		}
	}
}
